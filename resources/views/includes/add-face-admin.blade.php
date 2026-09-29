<input type="file" id="fileInput">
<button onclick="registerFace('{{ addslashes(json_encode($username)) }}')">Daftarkan Wajah</button>
<script>
    async function registerFace(username) {
        const fileInput = document.getElementById("fileInput");
        const formData = new FormData();
        formData.append("username", username);
        formData.append("file", fileInput.files[0]);

        const response = await fetch("http://127.0.0.1:5050/register_face", {
            method: "POST",
            body: formData
        });
        
        const data = await response.json();
        console.log(data);
    }
</script>