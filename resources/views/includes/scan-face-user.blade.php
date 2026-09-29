<button id="start-button" class="btn btn-primary" type="button">Otentifikasi Wajah</button>

<div id="absen-container" style="display: none;">
    <video id="video" autoplay></video>
    <canvas id="canvas" style="display: none;"></canvas>
</div>

<script defer>
    const userId = "{{ $user_id }}";
    const video = document.getElementById("video");
    const canvas = document.getElementById("canvas");
    const ctx = canvas.getContext("2d");
    const attendanceForm = document.getElementById("form-data");
    const startButton = document.getElementById("start-button");
    const absenContainer = document.getElementById("absen-container");

    let ws;
    let isWebcamActive = false;
    let isAuthenticated = false;
    let videoStream;

    function showAlert(icon, title, text, timer = 2000) {
        Swal.fire({
            icon: icon,
            title: title,
            text: text,
            showConfirmButton: false,
            timer: timer
        });
    }

    function startAbsen() {
        absenContainer.style.display = "block";
        startButton.style.display = "none";

        if (!isWebcamActive) {
            isWebcamActive = true;

            try {
                ws = new WebSocket("ws://127.0.0.1:5050/ws");

                ws.onopen = () => console.log("WebSocket connected");

                ws.onerror = () => {
                    showAlert('error', 'WebSocket Gagal', 'Tidak dapat terhubung ke server otentikasi wajah.');
                };

                ws.onmessage = (event) => {
                    if (isAuthenticated) return;

                    try {
                        const data = JSON.parse(event.data);

                        if (data.face) {
                            if (data.face == userId) {
                                isAuthenticated = true;
                                showAlert('success', 'Otentifikasi Berhasil', 'Wajah Anda dikenali');

                                setTimeout(() => {
                                    stopWebcam();
                                    ws.close();
                                    attendanceForm.submit();
                                }, 1000);

                            } else {
                                showAlert('error', 'Wajah Tidak Cocok', 'Wajah yang terdeteksi tidak sesuai dengan akun Anda.');
                            }
                        } else {
                            showAlert('warning', 'Tidak Terdeteksi', 'Tidak ada wajah yang terdeteksi, coba lagi.');
                        }
                    } catch (e) {
                        console.error("Invalid JSON from server:", e);
                        showAlert('error', 'Data Error', 'Format data tidak dikenali.');
                    }
                };

                navigator.mediaDevices.getUserMedia({ video: true })
                    .then(stream => {
                        video.srcObject = stream;
                        videoStream = stream;
                        video.play();
                        captureAndSendFrame();
                    })
                    .catch(err => {
                        console.error("Error accessing webcam:", err);
                        showAlert('error', 'Akses Kamera Gagal', 'Pastikan kamera diizinkan dan tersedia.');
                    });

            } catch (e) {
                showAlert('error', 'Error', 'Kesalahan saat memulai otentikasi.');
            }
        }
    }

    function stopWebcam() {
        if (videoStream) {
            videoStream.getTracks().forEach(track => track.stop());
        }
        isWebcamActive = false;
    }

    async function captureAndSendFrame() {
        if (isAuthenticated) return;

        const width = 640, height = 480;
        canvas.width = width;
        canvas.height = height;

        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            ctx.drawImage(video, 0, 0, width, height);
            const imageData = canvas.toDataURL("image/jpeg");
            const base64Data = imageData.split(",")[1];

            if (ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({ image: base64Data }));
            }
        }

        requestAnimationFrame(captureAndSendFrame);
    }

    startButton.addEventListener("click", startAbsen);
</script>
