const columns = document.querySelector('input[name="columns"]');
const rows = document.querySelector('input[name="rows"]');

// Resizing the game
document.getElementById("resizeGameBtn").addEventListener("click", function(event){
    event.preventDefault();
    // Previous Image is in "functions.js" saved after clicking on an image.
    reinit(newLoadedImage);
    initPuzzle();
});

let finished = true;
// Restarting the game
document.getElementById("restart").addEventListener("click", function(event){
    event.preventDefault();
    console.log("Restarting!");
    initPuzzle();
});

// Default options
document.getElementById("reset").addEventListener("click", function(event){
    event.preventDefault();
    document.getElementById("columns").value = 4;
    document.getElementById("rows").value = 4;
    initPuzzle();
});

// Touch
let touchScreen = false;
window.addEventListener('touchstart', function(e) {
    touchScreen = true;
    return true;
});

const PUZZLE_HOVER_TINT = "#FF2D00";
var cnvs;
var _stage;
var _img; 
var _pieces; 
var _puzzleWidth; 
var _puzzleHeight; 
var _pieceWidth;
var _pieceHeight; 
var _currentPiece; 
var _currentDropPiece; 
var _mouse; 

function init(){
    console.log("Halo2");
    _img = new Image();
    _img.addEventListener("load",onImage);
    _img.src = "images/8.png";
}

function reinit(newimgurl){
    console.log("Halo");
    _img = new Image();
    _img.addEventListener("load",onImage);
    _img.src = newimgurl;
}

function onImage(){
    _pieceWidth = Math.floor(_img.width / rows.value)
    _pieceHeight = Math.floor(_img.height / columns.value)
    _puzzleWidth = _pieceWidth * rows.value;
    _puzzleHeight = _pieceHeight * columns.value;
    while(screen.width/1.2 < _puzzleWidth){
        _pieceWidth /= 1.1;
        _pieceHeight /= 1.1; 
        _puzzleWidth /= 1.1;
        _puzzleHeight /= 1.1;
    }
    console.log("PuzzleWidth: " + _puzzleWidth + " PuzzleHeight: " + _puzzleHeight);
    console.log(screen.width);
    setCanvas();
    initPuzzle();
}
    
function setCanvas(){
    cnvs = document.getElementById("puzzleCanvas");
    _stage = cnvs.getContext("2d");
    cnvs.width = _puzzleWidth;
    cnvs.height = _puzzleHeight;
    cnvs.style.border = "1px solid white";
}
    
function initPuzzle(){
    _pieces = [];
    _mouse = {x:0,y:0};
    _currentPiece = null; 
    _currentDropPiece = null;
    _stage.drawImage(_img, 0, 0, _puzzleWidth, _puzzleHeight,
    0, 0, _puzzleWidth, _puzzleHeight);
    createTitle("Start the Game");
    buildPieces();
}

function createTitle(msg){
    _stage.fillStyle = "#000000";
    _stage.globalAlpha = .4;
    _stage.fillRect(100,_puzzleHeight - 40,_puzzleWidth - 200,40);
    _stage.fillStyle = "#FFFFFF";
    _stage.globalAlpha = 1;
    _stage.textAlign = "center";
    _stage.textBaseline = "middle";
    _stage.font = "20px Arial";
    _stage.fillText(msg,_puzzleWidth / 2,_puzzleHeight - 20);
}
    
function buildPieces(){
    var i;
    var piece;
    var xPos = 0;
    var yPos = 0;
    for(i = 0;i < rows.value * columns.value;i++){
        piece = {};
        piece.sx = xPos;
        piece.sy = yPos;
        _pieces.push(piece);
        xPos += _pieceWidth;
        if(xPos >= _puzzleWidth){
            xPos = 0;
            yPos += _pieceHeight;
        }
    }
    console.log("Halo3");
    if(touchScreen){
        console.log("TouchScreen Detected (shuffle)");
        document.ontouchstart = shufflePuzzle;
    }else{
        console.log("TouchScreen Not Detected (shuffle)");
        document.onmousedown = shufflePuzzle;
    }
}

function shufflePuzzle(){
    if(touchScreen) document.onmousedown = console.log("This is stupid fix overriding onmousedown.");
    _pieces = shuffleArray(_pieces);
    _stage.clearRect(0,0,_puzzleWidth,_puzzleHeight);
    var i;
    var piece;
    var xPos = 0;
    var yPos = 0;
    for(i = 0;i < _pieces.length;i++){
        piece = _pieces[i];
        piece.xPos = xPos;
        piece.yPos = yPos;

        if(i == 0){
            _stage.globalAlpha = 0.5;
            _stage.fillStyle = "#FF0000";
            _stage.fillRect(xPos, yPos, _pieceWidth,_pieceHeight);
            _stage.stroke();
            _stage.globalAlpha = 1.0;
        }else{
            _stage.drawImage(_img, piece.sx, piece.sy, _pieceWidth, _pieceHeight, xPos, yPos, _pieceWidth, _pieceHeight);
        }

        _stage.strokeRect(xPos, yPos, _pieceWidth,_pieceHeight);

        console.log(`Puzzle ${i}: x: ${piece.xPos}, y: ${piece.yPos}`);
        xPos += _pieceWidth;
        if(xPos >= _puzzleWidth){
            xPos = 0;
            yPos += _pieceHeight;
        }
    }
    console.log("TouchScreen:" + touchScreen);
    if(touchScreen){
        document.ontouchstart = onPuzzleClick;
    }else{
        document.onmousedown = onPuzzleClick;
    }
}

function onPuzzleClick(e){
    if (e.target == document.getElementById("puzzleCanvas")) {

        if(touchScreen){
            console.log("XD");
            var rect = e.target.getBoundingClientRect();
            _mouse.x = e.targetTouches[0].pageX - rect.left;
            _mouse.y = e.targetTouches[0].pageY - rect.top;
        }else{
            console.log("X--D");
            _mouse.x = e.layerX;
            _mouse.y = e.layerY;
        }
    
        console.log("[Detected Mouse/Touch] X: " + _mouse.x + " Y: " + _mouse.y);

        _currentPiece = checkPieceClicked();
        
        if(_currentPiece != null){
            _stage.clearRect(_currentPiece.xPos,_currentPiece.yPos,_pieceWidth,_pieceHeight);
            _stage.save(); 
            _stage.globalAlpha = .9;
            _stage.drawImage(_img, _currentPiece.sx, _currentPiece.sy,_pieceWidth, _pieceHeight, _mouse.x - (_pieceWidth / 2), _mouse.y - (_pieceHeight / 2), _pieceWidth, _pieceHeight);
            _stage.restore();
            document.onmousemove = updatePuzzle;
            document.ontouchmove = updatePuzzle;
            document.onmouseup = pieceDropped;
            document.ontouchend = pieceDropped;
        }
        return;
    }
    console.log("Clicked outside of the Canvas (fix for any weird behavior).");
}

function checkPieceClicked(){
    var i;
    var piece;
    for(i = 0;i < _pieces.length;i++){
        piece = _pieces[i];
        if(_mouse.x < piece.xPos || _mouse.x > (piece.xPos + _pieceWidth) || _mouse.y < piece.yPos || _mouse.y > (piece.yPos + _pieceHeight)){
            //PIECE NOT HIT
        }else{
            if(detectRedBlock(piece)) return piece;
        }
    }
    return null;
}



function detectRedBlock(piece){
    console.log(piece);
    if(piece.xPos - _pieceWidth == _pieces[0].xPos && piece.yPos == _pieces[0].yPos) return true;
    if(piece.xPos + _pieceWidth == _pieces[0].xPos && piece.yPos == _pieces[0].yPos) return true;
    if(piece.xPos == _pieces[0].xPos && piece.yPos - _pieceHeight == _pieces[0].yPos) return true;
    if(piece.xPos == _pieces[0].xPos && piece.yPos + _pieceHeight == _pieces[0].yPos) return true;
}
    
function updatePuzzle(e){
    _currentDropPiece = null;

    if(touchScreen){
        // console.log("XD");
        var rect = e.target.getBoundingClientRect();
        _mouse.x = e.targetTouches[0].pageX - rect.left;
        _mouse.y = e.targetTouches[0].pageY - rect.top;
    }else{
        // console.log("X--D");
        _mouse.x = e.layerX;
        _mouse.y = e.layerY;
    }

    _stage.clearRect(0,0,_puzzleWidth,_puzzleHeight);
    var i;
    var piece;
    for(i = 0;i < _pieces.length;i++){
        piece = _pieces[i];
        if(piece == _currentPiece){
            continue;
        }
        
        if(i == 0){
            _stage.globalAlpha = 0.5;
            _stage.fillStyle = "#FF0000";
            _stage.fillRect(piece.xPos, piece.yPos, _pieceWidth,_pieceHeight);
            _stage.stroke();
            _stage.globalAlpha = 1.0;
        }else{
            _stage.drawImage(_img, piece.sx, piece.sy, _pieceWidth, _pieceHeight, piece.xPos, piece.yPos, _pieceWidth, _pieceHeight);
        }

        _stage.strokeRect(piece.xPos, piece.yPos, _pieceWidth,_pieceHeight);
        if(_currentDropPiece == null){
            if(_mouse.x < piece.xPos || _mouse.x > (piece.xPos + _pieceWidth) || _mouse.y < piece.yPos || _mouse.y > (piece.yPos + _pieceHeight)){
                //NOT OVER
            }else{
                _currentDropPiece = piece;
                _stage.save();
                _stage.globalAlpha = .4;
                _stage.fillStyle = PUZZLE_HOVER_TINT;
                _stage.fillRect(_currentDropPiece.xPos,
                _currentDropPiece.yPos,_pieceWidth,
                _pieceHeight);
                _stage.restore();
            }
        }
    }
        
    _stage.save();
    _stage.globalAlpha = .6;
    _stage.drawImage(_img, _currentPiece.sx, _currentPiece.sy, _pieceWidth, _pieceHeight, _mouse.x - (_pieceWidth / 2), _mouse.y - (_pieceHeight / 2), _pieceWidth, _pieceHeight);
    _stage.restore();
    _stage.strokeRect( _mouse.x - (_pieceWidth / 2), _mouse.y - (_pieceHeight / 2), _pieceWidth,_pieceHeight);
}

function pieceDropped(e){
    document.onmousemove = null;
    document.onmouseup = null;
    document.ontouchmove = null;
    document.ontouchend = null;
    if(_currentDropPiece != null){
        if(_currentDropPiece.xPos == _pieces[0].xPos && _currentDropPiece.yPos == _pieces[0].yPos){

        var tmp = {xPos:_currentPiece.xPos,yPos:_currentPiece.yPos};
        _currentPiece.xPos = _currentDropPiece.xPos;
        _currentPiece.yPos = _currentDropPiece.yPos;
        _currentDropPiece.xPos = tmp.xPos;
        _currentDropPiece.yPos = tmp.yPos;
        }
    }
    resetPuzzleAndCheckWin();
}
    
function resetPuzzleAndCheckWin(){
    _stage.clearRect(0,0,_puzzleWidth,_puzzleHeight);
    var gameWin = true;
    var i;
    var piece;
    for(i = 0;i < _pieces.length;i++){
        piece = _pieces[i];

        if(i == 0){
            _stage.globalAlpha = 0.5;
            _stage.fillStyle = "#FF0000";
            _stage.fillRect(piece.xPos, piece.yPos, _pieceWidth,_pieceHeight);
            _stage.stroke();
            _stage.globalAlpha = 1.0;
        }else{
            _stage.drawImage(_img, piece.sx, piece.sy, _pieceWidth, _pieceHeight, piece.xPos, piece.yPos, _pieceWidth, _pieceHeight);
        }

        _stage.strokeRect(piece.xPos, piece.yPos,
        _pieceWidth,_pieceHeight);
        if(piece.xPos != piece.sx || piece.yPos != piece.sy){
            gameWin = false;
        }
    }
    if(gameWin){
        setTimeout(gameOver,500);
    }
}
        
function gameOver(){
    document.onmousedown = null;
    document.onmousemove = null;
    document.onmouseup = null;
    document.ontouchstart = null;
    document.ontouchmove = null;
    document.ontouchend = null;
    initPuzzle();
}