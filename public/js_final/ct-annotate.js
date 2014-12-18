ct_annotate = {};
ct_annotate.drag = false;
ct_annotate.curr_rect = [];
ct_annotate.all_rects = [];

ct_annotate.loadCanvasImage = function (canvas, imageUrl, doRect) {
	ct_annotate.imageObj = new Image();
	
	ct_annotate.imageObj.onload = function() {
		canvas.height = ct_annotate.imageObj.height;
		canvas.width = ct_annotate.imageObj.width;
		
		ct_annotate.canvas = canvas;
		ct_annotate.ctx = canvas.getContext('2d');
		draw();
	};
	ct_annotate.imageObj.src = imageUrl;
	
	canvas.addEventListener('mousedown', mouseDown, false);
	canvas.addEventListener('mouseup', mouseUp, false);
	canvas.addEventListener('mousemove', mouseMove, false);
	
	if(doRect) {
		ct_annotate.drawShape = drawRectangle;
	} else {
		ct_annotate.drawShape = drawEllipse;
	}
}

function mouseDown(e) {
	// initialize start point
	ct_annotate.curr_rect.start_x = getCanvasX(e);
	ct_annotate.curr_rect.start_y = getCanvasY(e);
	
	// initialize size
	ct_annotate.curr_rect.size_width = 0;
	ct_annotate.curr_rect.size_height = 0;
	
	ct_annotate.drag = true;
}

function mouseUp(e) {
	ct_annotate.drag = false;
	ct_annotate.all_rects.push([ ct_annotate.curr_rect.start_x, ct_annotate.curr_rect.start_y, 
				ct_annotate.curr_rect.size_width, ct_annotate.curr_rect.size_height ]);
	draw();
}

function getCanvasX(e) {
	offsets = ct_annotate.canvas.getBoundingClientRect();
	return e.clientX - offsets.left;
}

function getCanvasY(e) {
	offsets = canvas.getBoundingClientRect();
	return e.clientY - offsets.top;
}

function mouseMove(e) {
	if (ct_annotate.drag) {
		ct_annotate.curr_rect.size_width = getCanvasX(e) - ct_annotate.curr_rect.start_x;
		ct_annotate.curr_rect.size_height = getCanvasY(e) - ct_annotate.curr_rect.start_y;
		draw();
	}
}

function draw() {
	// Clear canvas -- but keep image
	ct_annotate.ctx.drawImage(ct_annotate.imageObj, 0, 0);
	
	// If currently dragging -- draw red shape
	ct_annotate.ctx.beginPath();
	if(ct_annotate.drag) {
		drawAnnotation(ct_annotate.curr_rect.start_x, ct_annotate.curr_rect.start_y, 
				ct_annotate.curr_rect.size_width, ct_annotate.curr_rect.size_height);
		ct_annotate.ctx.strokeStyle="red";
	} else {
		for (idx in ct_annotate.all_rects) {
			[x,y,w,h] = ct_annotate.all_rects[idx];
			drawAnnotation(x,y,w,h);
		}
		ct_annotate.ctx.strokeStyle="green";
	}
	ct_annotate.ctx.stroke();
	ct_annotate.ctx.closePath();
}

function drawAnnotation(x,y,w,h) {
	if(Math.abs(w)<10 || Math.abs(h)<10) {
		drawDot(x,y)
	} else {
		ct_annotate.drawShape(x,y,w,h);
	}
}

function drawDot(x,y) {
	ct_annotate.ctx.rect(x,y,2,2)
}

function drawRectangle(x,y,w,h) {
	ct_annotate.ctx.rect(x,y,w,h);
}

function drawEllipse(x,y,w,h) {
	hB = (w / 2) * 0.5522848;
	vB = (h / 2) * 0.5522848;
	eX = x + w;
	eY = y + h;
	mX = x + w / 2;
	mY = y + h / 2;
	ct_annotate.ctx.moveTo(x, mY);
	ct_annotate.ctx.bezierCurveTo(x, mY - vB, mX - hB, y, mX, y);
	ct_annotate.ctx.bezierCurveTo(mX + hB, y, eX, mY - vB, eX, mY);
	ct_annotate.ctx.bezierCurveTo(eX, mY + vB, mX + hB, eY, mX, eY);
	ct_annotate.ctx.bezierCurveTo(mX - hB, eY, x, mY + vB, x, mY);
}
