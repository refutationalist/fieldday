
/* exchange handlers */

var exch = {
	class: "?",
	xmtr:  0,
	section: "??"
};



function drawexch() {
	let text = exch.xmtr + exch.class + "-" + exch.section;
	document.getElementById("tocopy").innerHTML = text;
}

function resetexch() {
	exch = {
		class: "?",
		xmtr:  0,
		section: "??"
	};
	document.getElementById("xmtr").value = 0;

	drawexch();
}

resetexch();


document.getElementById("cpy").addEventListener("click", function(evt) {
	let text = document.getElementById("tocopy").innerHTML;
	let cpy = document.getElementById("cpy");

	navigator.clipboard.writeText(text).then(function() {
		console.log("copied", text);
		cpy.innerHTML = "!!";
		setTimeout(function() {
			cpy.innerHTML = "Copy";
		}, 1000);
		/* clipboard successfully set */
	}, function() {
		alert("copy to clipboard failed");
		/* clipboard write failed */
	});

});

document.getElementById("clear").addEventListener("click", function(evt) {
	resetexch();
	let click = new Event('click');
	document.getElementById("to_class").dispatchEvent(click);
});


/* page toggle handlers */

function togpage(evt) {
	let target = evt.target.id;

	let tab = [
		document.getElementById("to_class"),
		document.getElementById("to_section")
	];

	let page = [
		document.getElementById("xandc"),
		document.getElementById("section")
	];



	if (target == "to_class") {
		tab[0].classList.add("active");
		tab[1].classList.remove("active");
		page[0].classList.add("active");
		page[1].classList.remove("active");
	} else {
		tab[1].classList.add("active");
		tab[0].classList.remove("active");
		page[1].classList.add("active");
		page[0].classList.remove("active");
	}
}


document.querySelector("#to_class").addEventListener("click", togpage);
document.querySelector("#to_section").addEventListener("click", togpage);


/* xmtr handlers */

document.getElementById("xmtr").addEventListener("change", function(evt) {
	let val = evt.target.value;
	exch.xmtr = val;
	drawexch();
});

function xmtrbutton(evt) {
	let val = evt.target.innerHTML;
	let xmtr = document.getElementById("xmtr");
	let event = new Event('change');

	xmtr.value = val;
	xmtr.dispatchEvent(event);
}
xbut = document.querySelectorAll("#xmtrbox li");
for (let i = 0 ; i < xbut.length ; i++)
	xbut[i].addEventListener("click", xmtrbutton);


/* class buttons */

function classbutton(evt) {
	let fdclass = evt.target.children[0].innerHTML;
	exch.class = fdclass;
	drawexch();
	
	let click = new Event('click');
	document.getElementById("to_section").dispatchEvent(click);


}
cbut = document.querySelectorAll("#classbox li");
for (let i = 0 ; i < cbut.length ; i++)
	cbut[i].addEventListener("click", classbutton);


/* section buttons */

function sectionbutton(evt) {
	let section = evt.target.children[0].innerHTML;
	exch.section = section;
	drawexch();
	
	let click = new Event('click');
	document.getElementById("cpy").dispatchEvent(click);

	document.getElementById("section").scrollTop = 0;


}
sbut = document.querySelectorAll("#section li");
for (let i = 0 ; i < sbut.length ; i++)
	sbut[i].addEventListener("click", sectionbutton);



