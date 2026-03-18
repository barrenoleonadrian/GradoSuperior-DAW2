const form = document.getElementById("gameForm");
const grid = document.getElementById("grid");

let hunterPosition;
let cells = [];

form.addEventListener("submit", function(e){

    e.preventDefault(); 
    const fil = document.getElementById("fil").value;
    const col = document.getElementById("col").value;
    generateGrid(fil, col);
    placeHunter();

});

function generateGrid(fil, col){

    grid.innerHTML = "";
    cells = [];
    grid.style.gridTemplateColumns = `repeat(${col}, 70px)`;

    for(let i = 0; i < fil * col; i++){
        const cell = document.createElement("div");
        cell.classList.add("celda");
        cell.dataset.index = i;
        cell.addEventListener("click", cellClick);
        grid.appendChild(cell);
        cells.push(cell);
    }

}

function placeHunter(){

    const randomIndex = Math.floor(Math.random() * cells.length);
    hunterPosition = randomIndex;
}
function cellClick(event){
    const cell = event.target;
    const index = cell.dataset.index;
    if(index == hunterPosition){
        cell.style.backgroundImage = "url('img/boat.png')";
        cell.style.backgroundSize = "cover";
        alert("Enhorabuena has encontrado al hunter");
    }else{
        cell.style.backgroundImage = "url('img/square.jpg')";
        cell.style.backgroundSize = "cover";
    }

}