let area = document.querySelectorAll("area");
let selectedPosition = document.querySelector('[name="selectedPosition"]')
let img = document.querySelector("#playground")
area.forEach(e => {
    e.addEventListener('click', () => {
        selectedPosition.value = e.id;
    })

})
