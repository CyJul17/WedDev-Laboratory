let boxes = document.querySelectorAll(".box");
let resetButton = document.querySelector("#resgame");
let winnerPop = document.querySelector("#winnerPop");
let winnerTxt = document.querySelector("#winnerTxt");
let restartGame = document.querySelector("#restart");

let turnX = true; // player X first

// [a, b, c]
const winningMove = [
        [0,1,2],
        [0,3,6],
        [0,4,8],
        [1,4,7],
        [2,5,8],
        [2,4,6],
        [3,4,5],
        [6,7,8]
];

boxes.forEach((box) => {
    box.addEventListener("click",() => {
        if (box.textContent === "") {
            box.textContent = turnX ? "X" : "O"; //if else statement
            turnX = !turnX;
            checkWinner();
        }
    });
});



function checkWinner() {
    let isWinner = false; // boolean to check is it's draw.
    winningMove.forEach((win) => {
        const [a,b,c] = win;
        if (boxes[a].textContent !== "" && 
            boxes[a].textContent === boxes[b].textContent &&
            boxes[a].textContent === boxes[c].textContent) {
                isWinner = true;
                showPop(winnerTxt.textContent = `Congratulation ${boxes[a].textContent} is the winner!`);
            }
        
    });
    //check if it's a draw or not.
    if(!isWinner) {
        //make the nodeList into array to use the "every"
        let allFilled = Array.from(boxes).every(box => box.textContent !== "");
            if(allFilled) {
                showPop("Draw!");
            }
    }
}
//fuction to show the Pop up 
function showPop(message) {
    winnerPop.style.display = "flex";
    winnerTxt.textContent = message;
}
restart.addEventListener("click", newGame)
resgame.addEventListener("click", gameReset);

function newGame() {
    winnerPop.style.display = "none"; // Erase the pop
    gameReset();
}

function gameReset() {
    boxes.forEach((box) => {
        box.textContent = "";
    });
    turnX = true;
    
}



