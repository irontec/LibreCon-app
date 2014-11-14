package com.irontec.librecon.ui;

import android.content.Context;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.graphics.Path;
import android.util.AttributeSet;
import android.view.View;

/**
 * Created by Asier Fernandez on 26/09/14.
 */
public class LineStatusView extends View {


    Paint paint;
    Path path;

    public LineStatusView(Context context) {
        super(context);
        init();
    }

    public LineStatusView(Context context, AttributeSet attrs) {
        super(context, attrs);
        init();
    }

    public LineStatusView(Context context, AttributeSet attrs, int defStyle) {
        super(context, attrs, defStyle);
        init();
    }

    public void setColor(int color) {
        paint.setColor(color);
    }

    private void init() {
        paint = new Paint();
        paint.setAntiAlias(true);
        paint.setStrokeWidth(15f);
        paint.setColor(Color.GREEN);
        paint.setStyle(Paint.Style.STROKE);
        paint.setStrokeJoin(Paint.Join.ROUND);
    }

    @Override
    protected void onDraw(Canvas canvas) {
        // TODO Auto-generated method stub
        super.onDraw(canvas);
        canvas.drawLine(0, canvas.getHeight() / 2, canvas.getWidth(), canvas.getHeight() / 2, paint);
        //drawCircle(cx, cy, radius, paint)
    }

}