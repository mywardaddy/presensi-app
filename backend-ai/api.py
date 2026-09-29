import asyncio
import cv2
import dlib
import numpy as np
import base64
import json
import os
from fastapi import FastAPI, WebSocket, WebSocketDisconnect, File, UploadFile, Form
from fastapi.responses import JSONResponse
from fastapi.middleware.cors import CORSMiddleware
from PIL import Image

app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=["http://127.0.0.1:8000", "http://localhost:8000"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

detector = dlib.get_frontal_face_detector()
shape_predictor = dlib.shape_predictor("model/shape_predictor_68_face_landmarks.dat")
face_rec_model = dlib.face_recognition_model_v1("model/dlib_face_recognition_resnet_model_v1.dat")

KNOWN_FACES_FILE = "known_faces.json"
DATASET_FOLDER = "images"
os.makedirs(DATASET_FOLDER, exist_ok=True)

def bytes_to_image(image_bytes):
    image_np = np.frombuffer(image_bytes, dtype=np.uint8)
    image = cv2.imdecode(image_np, cv2.IMREAD_COLOR)
    if image is None:
        raise ValueError("Gagal mendekode gambar dari bytes")
    return Image.fromarray(cv2.cvtColor(image, cv2.COLOR_BGR2RGB))

def load_known_faces():
    try:
        with open(KNOWN_FACES_FILE, "r") as f:
            data = f.read()
            return json.loads(data) if data else {}
    except (FileNotFoundError, json.decoder.JSONDecodeError):
        return {}

def save_known_faces(data):
    with open(KNOWN_FACES_FILE, "w") as f:
        json.dump(data, f)

KNOWN_FACES = load_known_faces()

def extract_face_embedding(image_np):
    gray = cv2.cvtColor(image_np, cv2.COLOR_BGR2GRAY)
    faces = detector(gray)
    if len(faces) == 0:
        return None
    shape = shape_predictor(gray, faces[0])
    face_descriptor = face_rec_model.compute_face_descriptor(image_np, shape)
    return np.array(face_descriptor).tolist()

def register_face(name: str, image_np):
    face_embedding = extract_face_embedding(image_np)
    if face_embedding is None:
        return {"message": "Tidak ada wajah terdeteksi"}
    KNOWN_FACES[name] = face_embedding
    save_known_faces(KNOWN_FACES)
    return {"message": f"Registrasi Wajah Berhasil: {name}"}

async def recognize_face(image_np):
    face_embedding = extract_face_embedding(image_np)
    if face_embedding is None:
        return "No face detected"

    face_embedding = np.array(face_embedding)
    min_distance = float("inf")
    best_match = "Unknown"

    for name, known_embedding in KNOWN_FACES.items():
        known_embedding = np.array(known_embedding)
        distance = np.linalg.norm(known_embedding - face_embedding)
        if distance < 0.85 and distance < min_distance:
            min_distance = distance
            best_match = name

    return best_match

@app.get("/")
async def root():
    return {"message": "Face Recognition API is running!"}

@app.websocket("/ws")
async def websocket_endpoint(websocket: WebSocket):
    await websocket.accept()
    try:
        while True:
            data_json = await websocket.receive_text()
            data = json.loads(data_json)
            print("Menerima gambar base64")

            image_data = base64.b64decode(data["image"])
            with open("debug_frame.jpg", "wb") as f:
                f.write(image_data)

            image = bytes_to_image(image_data)
            image = np.array(image)
            name = await recognize_face(image)
            print("Recognized name:", name)
            await websocket.send_text(json.dumps({"face": name}))
            await asyncio.sleep(1)
    except WebSocketDisconnect:
        print("WebSocket disconnected")

@app.post("/register_face")
async def register_face_endpoint(
    id: str = Form(...),
    name: str = Form(...),
    file: UploadFile = File(...)
):
    if not file.content_type.startswith("image/"):
        return JSONResponse(content={"success": False, "message": "File bukan gambar yang valid"}, status_code=400)

    ext = os.path.splitext(file.filename)[1].lower()
    allowed_ext = ['.jpg', '.jpeg', '.png', '.webp']
    if ext not in allowed_ext:
        return JSONResponse(content={"success": False, "message": f"Format {ext} tidak didukung"}, status_code=400)

    # 🔹 Gunakan nama user untuk nama file (bukan id lagi)
    # misalnya: "Budi Santoso.jpg"
    safe_name = name.replace(" ", "_")  # ganti spasi biar aman di filesystem
    image_path = os.path.join(DATASET_FOLDER, f"{safe_name}{ext}")

    file_bytes = await file.read()
    with open(image_path, "wb") as buffer:
        buffer.write(file_bytes)

    try:
        image = bytes_to_image(file_bytes)
        image_np = np.array(image)
        # simpan embedding dengan nama juga
        result = register_face(name, image_np)
        return {"success": True, "message": result["message"]}
    except Exception as e:
        return JSONResponse(content={"success": False, "message": f"Terjadi kesalahan: {str(e)}"}, status_code=500)
    
