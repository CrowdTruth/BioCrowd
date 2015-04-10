/**
 * Initialize library variables.
 */
ct_annotate = {};
ct_annotate.drag = false;
ct_annotate.curr_rect = [];
ct_annotate.all_rects = [];
ct_annotate.min_size = 10; // Minimum size for an annotation to be drawn as a non-dot

/**
 * Load canvas with image to be annotated.
 * 
 * Params:
 *  canvas     HTML canvas to be used
 *  imageUrl   Image to be displayed
 *  doRect     True for rectangular annotations, false for ellipses
 *  styleDrag  Line colour to draw while annotation is being drawn
 *  styleFixed Line colour to draw once annotation has been made
 */
ct_annotate.loadCanvasImage = function(canvas, imageUrl, doRect, styleDrag, styleFixed) {
	if(doRect === undefined) { doRect = false; }
	if(styleDrag === undefined) { styleDrag = 'red'; }
	if(styleFixed === undefined) { styleFixed = 'yellow'; }
	
	ct_annotate.imageObj = new Image();
	
	ct_annotate.imageObj.onload = function() {
		canvas.height = ct_annotate.imageObj.height;
		canvas.width = ct_annotate.imageObj.width;
		
		ct_annotate.canvas = canvas;
		ct_annotate.ctx = canvas.getContext('2d');

		ct_annotate_draw();
	};
	ct_annotate.imageObj.src = imageUrl;
	
	canvas.addEventListener('mousedown', ct_annotate_mouseDown, false);
	canvas.addEventListener('mouseup', ct_annotate_mouseUp, false);
	canvas.addEventListener('mousemove', ct_annotate_mouseMove, false);
	
	// ct_annotate triggers 'annotationChanged' events on canvas.
	// Add custom events as follows:
	// canvas.addEventListener('annotationChanged', function() { /* code */ }, false);
	
	ct_annotate.strokeStyleDrag = styleDrag;
	ct_annotate.strokeStyleFixed = styleFixed;
	
	// Make cursor a cross
	canvas.style = 'cursor:crosshair';
	if(doRect) {
		ct_annotate.drawShape = ct_annotate_drawRectangle;
	} else {
		ct_annotate.drawShape = ct_annotate_drawEllipse;
	}
}

ct_annotate.doRectangle = function(doRect) {
	if(doRect) {
		ct_annotate.drawShape = ct_annotate_drawRectangle;
	} else {
		ct_annotate.drawShape = ct_annotate_drawEllipse;
	}
}

/**
 * Returns an array with all current annotations.
 */
ct_annotate.getAnnotations = function () {
	return ct_annotate.all_rects;
}

/**
 * Remove the last annotation made.
 */
ct_annotate.removeLast = function () {
	ct_annotate.all_rects.pop();
	ct_annotate_draw();
	// Notify of removed annotation
	ct_annotate.canvas.dispatchEvent(new Event('annotationChanged'));
}

/**
 * Remove the n-th annotation made.
 */
ct_annotate.removeN = function (n) {
	ct_annotate.all_rects.splice(n, 1);
	ct_annotate_draw();
	// Notify of removed annotation
	ct_annotate.canvas.dispatchEvent(new Event('annotationChanged'));
}

/* NOTE -- from here onwards internal functions are used -- these should not be used outside the library */

/**
 * Trigger event when mouse button ins clicked.
 * Start drawing annotation until mouse is released.
 */
function ct_annotate_mouseDown(e) {
	// initialize start point
	ct_annotate.curr_rect.start_x = ct_annotate_getCanvasX(e);
	ct_annotate.curr_rect.start_y = ct_annotate_getCanvasY(e);
	
	// initialize size
	ct_annotate.curr_rect.size_width = 0;
	ct_annotate.curr_rect.size_height = 0;
	
	ct_annotate.drag = true;
}

/**
 * Triggered when mouse is released.
 * Finalize drawing of annotation. Add it to list of known annotations.
 */
function ct_annotate_mouseUp(e) {
	ct_annotate.drag = false;
	ct_annotate.all_rects.push([ ct_annotate.curr_rect.start_x, ct_annotate.curr_rect.start_y, 
				ct_annotate.curr_rect.size_width, ct_annotate.curr_rect.size_height ]);
	ct_annotate_draw();
	// Notify of new annotation
	ct_annotate.canvas.dispatchEvent(new Event('annotationChanged'));
}

/**
 * Get X coord clicked on canvas.
 */
function ct_annotate_getCanvasX(e) {
	offsets = ct_annotate.canvas.getBoundingClientRect();
	return e.clientX - offsets.left;
}

/**
 * Get Y coord clicked on canvas.
 */
function ct_annotate_getCanvasY(e) {
	offsets = canvas.getBoundingClientRect();
	return e.clientY - offsets.top;
}

/**
 * Mouse moved event.
 * Update currently drawing annotation.
 */
function ct_annotate_mouseMove(e) {
	if (ct_annotate.drag) {
		ct_annotate.curr_rect.size_width = ct_annotate_getCanvasX(e) - ct_annotate.curr_rect.start_x;
		ct_annotate.curr_rect.size_height = ct_annotate_getCanvasY(e) - ct_annotate.curr_rect.start_y;
		ct_annotate_draw();
	}
}

/**
 * Redraw canvas.
 * Display background image. Draw annotations on top.
 */
function ct_annotate_draw() {
	// Clear canvas -- but keep image
	ct_annotate.ctx.drawImage(ct_annotate.imageObj, 0, 0);
	
	// If currently dragging -- draw red shape
	ct_annotate.ctx.beginPath();
	if(ct_annotate.drag) {
		for (idx in ct_annotate.all_rects) {
			//draw the existing rectangles
			xywh = ct_annotate.all_rects[idx];
			x = xywh[0];
			y = xywh[1];
			w = xywh[2];
			h = xywh[3];
			ct_annotate_drawAnnotation(x,y,w,h);
		}
		ct_annotate.ctx.strokeStyle = ct_annotate.strokeStyleFixed;
		ct_annotate.ctx.stroke();
		ct_annotate.ctx.closePath();
		ct_annotate.ctx.beginPath();
		//draw the shape that is currently dragged
		ct_annotate_drawAnnotation(ct_annotate.curr_rect.start_x, ct_annotate.curr_rect.start_y, 
				ct_annotate.curr_rect.size_width, ct_annotate.curr_rect.size_height);
		ct_annotate.ctx.strokeStyle = ct_annotate.strokeStyleDrag;
	} else {
		for (idx in ct_annotate.all_rects) {
			xywh = ct_annotate.all_rects[idx];
			x = xywh[0];
			y = xywh[1];
			w = xywh[2];
			h = xywh[3];
			ct_annotate_drawAnnotation(x,y,w,h);
		}
		ct_annotate.ctx.strokeStyle = ct_annotate.strokeStyleFixed;
	}
	ct_annotate.ctx.stroke();
	ct_annotate.ctx.closePath();
}

/**
 * Draw annotation depending on style -- shape (rectangle, ellipse) or dot
 * if smaller than given size.
 */
function ct_annotate_drawAnnotation(x,y,w,h) {
	if(Math.abs(w)<ct_annotate.min_size || Math.abs(h)<ct_annotate.min_size) {
		ct_annotate_drawDot(x,y);
	} else {
		ct_annotate.drawShape(x,y,w,h);
	}
}

/**
 * Draw a dot annotation at given location.
 */
function ct_annotate_drawDot(x,y) {
	ct_annotate.ctx.rect(x,y,2,2);
}

/**
 * Draw a rectangle annotation at given location, with the given dimensions.
 */
function ct_annotate_drawRectangle(x,y,w,h) {
	ct_annotate.ctx.rect(x,y,w,h);
}

/**
 * Draw an ellipse annotation at given location, with the given dimensions.
 */
function ct_annotate_drawEllipse(x,y,w,h) {
	hB = (w / 2) * 0.5522848;	// Excentricity of ellipse.
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
