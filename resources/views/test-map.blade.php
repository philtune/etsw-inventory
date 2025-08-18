<x-layouts.app>
	<!-- Styles -->
	<style>
		#chartdiv {
			width: 100%;
			height: 500px
		}
	</style>

	<!-- Resources -->
	<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

	<!-- Chart code -->
	<script>
		am5.ready(function() {

// Create root
			var root = am5.Root.new("chartdiv");

// Set themes
			root.setThemes([
				am5themes_Animated.new(root)
			]);

// Create chart
			var chart = root.container.children.push(am5map.MapChart.new(root, {
				panX: "rotateX",
				panY: "none",
				projection: am5map.geoAlbersUsa(),
				layout: root.horizontalLayout
			}));

// Create polygon series
			var polygonSeries = chart.series.push(am5map.MapPolygonSeries.new(root, {
				geoJSON: am5geodata_usaLow,
				valueField: "value",
				calculateAggregates: true
			}));

			polygonSeries.mapPolygons.template.setAll({
				tooltipText: "{name}: {value}"
			});

			polygonSeries.set("heatRules", [{
				target: polygonSeries.mapPolygons.template,
				dataField: "value",
				min: am5.color(0xffff00),
				max: am5.color(0x00ff00),
				key: "fill"
			}]);

			polygonSeries.mapPolygons.template.events.on("pointerover", function(ev) {
				heatLegend.showValue(ev.target.dataItem.get("value"));
			});

			polygonSeries.data.setAll([
				{ id: "US-TN", value: 21601.99 },
				{ id: "US-CA", value: 11354.88 },
				{ id: "US-NY", value: 9704.18 },
				{ id: "US-FL", value: 7306.15 },
				{ id: "US-TX", value: 7021.04 },
				{ id: "US-NC", value: 6767.72 },
				{ id: "US-PA", value: 6730.20 },
				{ id: "US-IL", value: 6145.37 },
				{ id: "US-OH", value: 5518.49 },
				{ id: "US-MI", value: 5096.68 },
				{ id: "US-VA", value: 4798.58 },
				{ id: "US-MA", value: 4407.02 },
				{ id: "US-WA", value: 3819.21 },
				{ id: "US-WI", value: 3604.48 },
				{ id: "US-MN", value: 3437.98 },
				{ id: "US-MO", value: 3274.91 },
				{ id: "US-GA", value: 3273.96 },
				{ id: "US-CO", value: 2914.31 },
				{ id: "US-NJ", value: 2836.68 },
				{ id: "US-OR", value: 2585.55 },
				{ id: "US-MD", value: 2368.33 },
				{ id: "US-KY", value: 2243.98 },
				{ id: "US-IN", value: 2148.82 },
				{ id: "US-LA", value: 1972.75 },
				{ id: "US-AL", value: 1750.53 },
				{ id: "US-SC", value: 1507.83 },
				{ id: "US-CT", value: 1354.12 },
				{ id: "US-AZ", value: 1269.72 },
				{ id: "US-NV", value: 1115.47 },
				{ id: "US-OK", value: 1080.83 },
				{ id: "US-UT", value: 957.60 },
				{ id: "US-WV", value: 947.37 },
				{ id: "US-ID", value: 930.62 },
				{ id: "US-AK", value: 926.43 },
				{ id: "US-NE", value: 908.86 },
				{ id: "US-IA", value: 878.90 },
				{ id: "US-WY", value: 827.54 },
				{ id: "US-KS", value: 646.38 },
				{ id: "US-AR", value: 638.12 },
				{ id: "US-HI", value: 634.37 },
				{ id: "US-MS", value: 574.32 },
				{ id: "US-ND", value: 552.26 },
				{ id: "US-VT", value: 548.95 },
				{ id: "US-ME", value: 527.48 },
				{ id: "US-DE", value: 494.07 },
				{ id: "US-NH", value: 479.94 },
				{ id: "US-NM", value: 467.61 },
				{ id: "US-AE", value: 389.97 },
				{ id: "US-RI", value: 372.46 },
				{ id: "US-MT", value: 343.73 },
				{ id: "US-DC", value: 330.14 },
				{ id: "US-SD", value: 272.89 },
				{ id: "US-AP", value: 150.36 },
				{ id: "US-PR", value: 99.02 },
				{ id: "US-GU", value: 33.57 },
				{ id: "US-ON", value: 10.99 }
			]);

			var heatLegend = chart.children.push(am5.HeatLegend.new(root, {
				orientation: "vertical",
				startColor: am5.color(0xffff00),
				endColor: am5.color(0x00ff00),
				startText: "Lowest",
				endText: "Highest",
				stepCount: 5
			}));

			heatLegend.startLabel.setAll({
				fontSize: 12,
				fill: heatLegend.get("startColor")
			});

			heatLegend.endLabel.setAll({
				fontSize: 12,
				fill: heatLegend.get("endColor")
			});

// change this to template when possible
			polygonSeries.events.on("datavalidated", function () {
				heatLegend.set("startValue", polygonSeries.getPrivate("valueLow"));
				heatLegend.set("endValue", polygonSeries.getPrivate("valueHigh"));
			});

		}); // end am5.ready()
	</script>

	<!-- HTML -->
	<div id="chartdiv"></div>
</x-layouts.app>
