function uploadFile() {
    var form = new FormData();
    form.append('file', document.querySelector('#imageFile').files[0]);
    form.append('upload', true);

    var upload = new XMLHttpRequest();
    upload.open('POST', 'upload.php');
    upload.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            if(this.responseText == 1) {
                document.querySelector('#uploadError').innerText = "Image uploaded successfully.";
                setTimeout(window.location.reload(), 1500);
            } else {
                document.querySelector('#uploadError').innerText = "An error occoured when uploading the image";
            }
        }
    };
    upload.send(form);
}
document.querySelector('upload').addEventListener('click', uploadFile())