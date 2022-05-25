import {Terminal} from "xterm"
import { FitAddon } from 'xterm-addon-fit';
import $ from "jquery"

var term = new Terminal();
var lastTermSize = {cols: 0, rows: 0}        
var fitAddon = new FitAddon();
term.loadAddon(fitAddon);

$(function(){
    term.open(document.getElementById('terminal'));
    fitAddon.fit();
    //term.resize(100,50);
})

/* --- Simple script making writing into the terminal possible
term.onKey(e => {
	console.log(e.key);
    term.write(e.key);
    if (e.key == '\r')
      		term.write('\n');
})*/

function updateTerminal()
{
    fitAddon.fit();
    var size = {rows: term.rows, cols: term.cols}
    var redraw = false
    if (size.cols != lastTermSize.cols || size.rows !== lastTermSize.rows) {
        redraw = true
    }
    lastTermSize = size
    

    var fullUrl = treeUrl + "?rows=" + size.rows + "&cols=" + size.cols + "&redraw=" + (redraw ? "true" : "false")
    $.ajax({
        type: "GET",   
        url: fullUrl,   
        success : function(data){
            term.write(data)
        }
    });
}

$(function () {
    setInterval(() => updateTerminal(), 1000)
})
