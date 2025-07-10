"use strict";

fetch("electiondata.php")
	.then(response => response.json())
	.then(data => {
        console.log(data);
        constTable(data);
	})
	.catch(error => {
		console.error("error", error);
	});

function constTable(data) {
    // table base
    const table = document.createElement("table");
    const tr = document.createElement("tr");
    const th1 = document.createElement("th");
    const th2 = document.createElement("th");
    th1.appendChild(document.createTextNode("Partij"));
    th2.appendChild(document.createTextNode("Zetels"));
    tr.appendChild(th1);
    tr.appendChild(th2);
    table.appendChild(tr);
    document.querySelector("main").appendChild(table);
    
    for (const entry in data) {
        constTableRow(data[entry]);
    }
}
function constTableRow(data) {
    console.log(data);
    const tr = document.createElement("tr");
    const td1 = document.createElement("td");
    const td2 = document.createElement("td");
    td1.appendChild(document.createTextNode(data.party));

    for (let count = 0; count < data.seats; count++) {
        const i = document.createElement("i");
        i.className = "fa-solid fa-circle"
        td2.appendChild(i);
    }

    td2.style.color = data.color;
    tr.appendChild(td1);
    tr.appendChild(td2);
    document.querySelector("table").appendChild(tr);
}