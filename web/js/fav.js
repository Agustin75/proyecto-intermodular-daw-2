

window.onload = () => {
    let inputs = document.getElementsByTagName('input');
    Array.from(inputs).forEach(element => {
        element.addEventListener('click', func_sendcheck);
    });


}

async function func_sendcheck(event) {
    let value = event.target.previousElementSibling.value;
    let value2 = event.target.checked;

    value2 = value2 == true ? 1 : 0;

    console.log(value2);
    let petition = new Request(`index.php?ctl=seleccionarFavorito&id=${value}&act=${value2}`,
        {
            method: "GET",
        });
console.log(petition);
    try {
        let info = await fetch(petition);
        if (info.ok) {
            // let data = await info.json();

        } else {
            console.log(info.status);
        }
    } catch (error) {
        console.log(error);
    }
}