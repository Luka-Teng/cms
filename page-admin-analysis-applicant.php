<?php get_header('admin'); ?>
<?php if (current_user_can( 'administrator' )) : ?>
	<div id="main" style="width:100%;height:700px;box-shadow: 0 0 3px 1px rgba(0,0,0,0.3)"></div>
	<script type="text/javascript">
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('main'));

        // 指定图表的配置项和数据
       var xData = function() {
			var data = [];
			for (var i = 1; i < 6; i++) {
				data.push(i + "月份");
			}
			return data;
		}();

		option = {
			backgroundColor: "#344b58",
			"title": {
				"text": "申请人数据分析",
				"subtext": "BY Luka",
				x: "4%",
				y: "20px",
				textStyle: {
					color: '#fff',
					fontSize: '22'
				},
				subtextStyle: {
					color: '#90979c',
					fontSize: '16',

				},
			},
			"tooltip": {
				"trigger": "axis",
				"axisPointer": {
					"type": "shadow",
					textStyle: {
						color: "#fff"
					}

				},
			},
			"grid": {
				"borderWidth": 0,
				"top": 150,
				"bottom": 95,
				textStyle: {
					color: "#fff"
				}
			},
			"legend": {
				x: '4%',
				top: '12%',
				textStyle: {
					color: '#90979c',
				},
				"data": ['媒体', '观众', '参展商', '总数']
			},
			 

			"calculable": true,
			"xAxis": [{
				"type": "category",
				"axisLine": {
					lineStyle: {
						color: '#90979c'
					}
				},
				"splitLine": {
					"show": false
				},
				"axisTick": {
					"show": false
				},
				"splitArea": {
					"show": false
				},
				"axisLabel": {
					"interval": 0,

				},
				"data": xData,
			}],
			"yAxis": [{
				"type": "value",
				"splitLine": {
					"show": false
				},
				"axisLine": {
					lineStyle: {
						color: '#90979c'
					}
				},
				"axisTick": {
					"show": false
				},
				"axisLabel": {
					"interval": 0,

				},
				"splitArea": {
					"show": false
				},

			}],
			"series": [
			{
				"name": "媒体",
				"type": "bar",
				"stack": "总量",
				"barMaxWidth": 55,
				"barGap": "10%",
				"itemStyle": {
					"normal": {
						"color": "rgba(255,144,128,1)",
						"label": {
							"show": true,
							"textStyle": {
								"color": "#fff"
							},
							"position": "inside",
							formatter: function(p) {
								return p.value > 0 ? (p.value) : '';
							}
						}
					}
				},
				"data": [
					899,
					786,
					281,
					289,
					675
				],
			},
			{
				"name": "观众",
				"type": "bar",
				"stack": "总量",
				"itemStyle": {
					"normal": {
						"color": "rgba(0,191,183,1)",
						"barBorderRadius": 0,
						"label": {
							"show": true,
							"position": "inside",
							formatter: function(p) {
								return p.value > 0 ? (p.value) : '';
							}
						}
					}
				},
				"data": [
					327,
					776,
					507,
					987,
					892
				]
			}, 
			{
				"name": "参展商",
				"type": "bar",
				"stack": "总量",
				"itemStyle": {
					"normal": {
						"color": "rgba(100,149,237,1)",
						"barBorderRadius": 0,
						"label": {
							"show": true,
							"position": "top",
							formatter: function(p) {
								return p.value > 0 ? (p.value) : '';
							}
						}
					}
				},
				"data": [
					367,
					456,
					675,
					289,
					777
				]
			}, 
			{
				"name": "总数",
				"type": "line",
				"stack": "总量",
				symbolSize:10,
				symbol:'circle',
				"itemStyle": {
					"normal": {
						"color": "rgba(252,230,48,1)",
						"barBorderRadius": 0,
						"label": {
							"show": true,
							"position": "top",
							formatter: function(p) {
								return p.value > 0 ? (p.value) : '';
							}
						}
					}
				},
				"data": [
					1593,
					2018,
					1445,
					1565,
					2344,
				]
			},
			]
		}

        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
    </script>
<?php else : ?>
	<script>
		window.location = '/login'
	</script>
<?php endif ?>
<?php get_footer('admin'); ?>