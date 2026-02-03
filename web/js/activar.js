

window.onload = () => {
    let inputs = document.getElementsByClassName('input');
    Array.from(inputs).forEach(element => {
        element.addEventListener('click', func_sendcheck);
    });


}

async function func_sendcheck(event) {
    let value = event.target.previosElementSibling.value;
    let value2 = event.target.value;
    console.log(value);
    let petition = new Request(`index.php?ctl=activarUser&id=${value}&act=${value2}`,
        {
            method: "GET",
        });

    try {
        let info = await fetch(petition);
        if (info.ok) {
            let data = await info.json();

        } else {
            console.log(info.status);
        }
    } catch (error) {
        console.log(error);
    }
}
