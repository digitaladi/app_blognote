document.querySelector("#add_trick_form_featureimage").addEventListener("change", checkFile);

function checkFile(){
    let preview = document.querySelector(".preview");
    let image = preview.querySelector("img");
    //this l'élément sur lequel on a appliqué l'evement
    let file = this.files[0];
    console.log(file)
    const types = ["image/jpeg", "image/png", "image/webp"];
    let reader = new FileReader();
   // alert(file);

    reader.onloadend = function(){
        image.src = reader.result;
        preview.style.display = "block";
    }

    // On vérifie qu'un fichier existe
    if(file){
        // On vérifie que le fichier est bien une image acceptée
        if(types.includes(file.type)){
            reader.readAsDataURL(file);
        }
    }else{
        image.src = "";
        preview.style.display = "none"
    }
}