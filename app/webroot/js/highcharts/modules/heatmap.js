(function (a) {
    var c = a.seriesTypes, i = a.each;
    c.heatmap = a.extendClass(c.map, {useMapGeometry: !1, pointArrayMap: ["y", "value"], init: function () {
        c.map.prototype.init.apply(this, arguments);
        this.pointRange = this.options.colsize || 1
    }, translate: function () {
        var a = this.options, f = this.xAxis, c = this.yAxis;
        this.generatePoints();
        i(this.points, function (b) {
            var d = (a.colsize || 1) / 2, e = (a.rowsize || 1) / 2, g = Math.round(f.len - f.translate(b.x - d, 0, 1, 0, 1)), d = Math.round(f.len - f.translate(b.x + d, 0, 1, 0, 1)), h = Math.round(c.translate(b.y - e, 0,
                1, 0, 1)), e = Math.round(c.translate(b.y + e, 0, 1, 0, 1));
            b.plotY = 1;
            b.shapeType = "rect";
            b.shapeArgs = {x: Math.min(g, d), y: Math.min(h, e), width: Math.abs(d - g), height: Math.abs(e - h)}
        });
        this.pointRange = a.colsize || 1;
        this.translateColors()
    }, animate: function () {
    }, getBox: function () {
    }, getExtremes: function () {
        a.Series.prototype.getExtremes.call(this, this.valueData);
        this.valueMin = this.dataMin;
        this.valueMax = this.dataMax;
        a.Series.prototype.getExtremes.call(this)
    }})
})(Highcharts);
