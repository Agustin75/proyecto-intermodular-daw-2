

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
    let petition = new Request(`index.php?ctl=activarUser&id=${value}&act=${value2}`,
        {
            method: "GET",
        });
console.log(petition);
    try {
        let info = await fetch(petition);
        if (info.ok) {
            // We update the visual feedback for the admin
            event.target.closest("tr").classList = event.target.checked ? "activo" : "inactivo";
        } else {
            console.log(info.status);
        }
    } catch (error) {
        console.log(error);
    }
}
