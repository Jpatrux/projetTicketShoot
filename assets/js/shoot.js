let area = document.querySelectorAll("path");
let selectedPosition = document.querySelector('[name="selectedPosition"]')
let img = document.querySelector("#playground")
area.forEach(e => {
    e.addEventListener('click', () => {
        area.forEach(e => {
            e.style.fill = 'white';
        })
        e.style.fill = '#C15C44';
        selectedPosition.value = e.id;
    })
})