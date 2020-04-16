import { Component, OnInit } from '@angular/core';
import {Chart} from 'node_modules/chart.js';
import { AdminService } from 'src/app/service/admin.service';

@Component({
  selector: 'app-diagram',
  templateUrl: './diagram.component.html',
  styleUrls: ['./diagram.component.scss']
})
export class DiagramComponent implements OnInit {
  public perseverancelabel=['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'];
  public perseverancedata=[12, 19, 3, 5, 2, 3];
  public confiancelabel=['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'];
  public confiancedata=[12, 19, 3, 5, 2, 3];
  public collaborationlabel=['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'];
  public collaborationdata=[12, 19, 3, 5, 2, 3];
  public autonomielabel=['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'];
  public autonomiedata=[12, 19, 3, 5, 2, 3];
  public problemsolvinglabel=['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'];
  public problemsolvingdata=[12, 19, 3, 5, 2, 3];
  public transmissionlabel=['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'];
  public transmissiondata=[12, 19, 3, 5, 2, 3];
  public performancelabel=['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'];
  public performancedata=[12, 19, 3, 5, 2, 3];
  constructor(private admin:AdminService) { }
  public data:any;
  ngOnInit() {
      console.log(this.admin.userdata);
      
      this.admin.data(this.admin.userdata).subscribe(
          res=>{
              console.log(res.body);
              this.data=res.body;
               this.perseverancelabel=this.data.perseverancelabel;
               console.log(this.perseverancelabel);
               
               this.perseverancedata=this.data.perseverancedata;
               this.confiancelabel=this.data.confiancelabel;
               this.confiancedata=this.data.confiancedata;
               console.log(this.confiancedata);
               
               this.collaborationlabel=this.data.collaborationlabel;
               this.collaborationdata=this.data.collaborationdata;
               this.autonomielabel=this.data.autonomielabel;
               this.autonomiedata=this.data.autonomiedata;
               this.problemsolvinglabel=this.data.problemsolvinglabel;
               this.problemsolvingdata=this.data.problemsolvingdata;
               this.transmissionlabel=this.data.transmissionlabel;
               this.transmissiondata=this.data.transmissiondata;
               this.performancelabel=this.data.performancelabel;
               this.performancedata=this.data.performancedata;
               this.perseverance();
               this.confiance();
               this.collaboration();
               this.autonomie();
               this.problemsolving();
               this.transmission();
               this.performance();
          },
          error=>{
              console.log(error);
              
          }
      )
    // this.perseverance();
    // this.confiance();
    // this.collaboration();
    // this.autonomie();
    // this.problemsolving();
    // this.transmission();
    // this.performance();
  }
  perseverance(){
    var rien = new Chart('perseverance', {
        type: 'bar',
        data: {
            labels: this.perseverancelabel,
            datasets: [{
                label: '# of Votes',
                data: this.perseverancedata,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor:'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    }
    confiance(){
        var rien1 = new Chart('confiance', {
            type: 'line',
			data: {
				labels: this.confiancelabel,
				datasets: [{
					label: 'My First dataset',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor:'rgba(255, 99, 132, 1)',
                    data: this.confiancedata,
                  //  borderWidth: 1,
					fill: false,
				}]
            },
            
			options: {
				responsive: true,
				title: {
					display: true,
					text: 'Chart.js Line Chart'
				},
				tooltips: {
					mode: 'index',
					intersect: false,
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
				scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Month'
						}
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Value'
						}
					}]
				}
			}

        });
        
    }
    collaboration(){
        var rien = new Chart('collaboration', {
            type: 'line',
			data: {
				labels: this.collaborationlabel,
				datasets: [{
					label: 'My First dataset',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor:'rgba(255, 99, 132, 1)',
					data: this.collaborationdata,
					fill: false,
				}]
			},
			options: {
				responsive: true,
				title: {
					display: true,
					text: 'Chart.js Line Chart'
				},
				tooltips: {
					mode: 'index',
					intersect: false,
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
				scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Month'
						}
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Value'
						},
						ticks: {
							min: 0,
							max: 5,

							// forces step size to be 5 units
							stepSize: 1
						}
					}]
				}
			}
        });
    }
    autonomie(){
        var rien = new Chart('autonomie', {
			type: 'line',
			data: {
				xLabels: this.autonomielabel,
				yLabels: [0,1, 2, 3, 4, 5],
				datasets: [{
					label: 'My First dataset',
					data: this.autonomiedata,
					fill: false,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor:'rgba(255, 99, 132, 1)',
				}]
			},
			options: {
				responsive: true,
				title: {
					display: true,
					text: 'Chart with Non Numeric Y Axis'
				},
				scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Month'
						}
					}],
					yAxes: [{
						type: 'category',
						position: 'left',
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Request State'
						},
						ticks: {
							reverse: true
						}
					}]
				}
			}
        });
    }
    problemsolving(){
        var rien = new Chart('problemsolving', {
            type: 'bar',
            data: {
                labels: this.problemsolvinglabel,
                datasets: [{
                    label: '# of Votes',
                    data: this.problemsolvingdata,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor:'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    }
    transmission(){
        var rien = new Chart('transmission', {
            type: 'bar',
            data: {
                labels: this.transmissionlabel,
                datasets: [{
                    label: '# of Votes',
                    data: this.transmissiondata,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor:'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    }
    performance(){
        var rien = new Chart('performance', {
            type: 'bar',
            data: {
                labels: this.performancelabel,
                datasets: [{
                    label: '# of Votes',
                    data: this.performancedata,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor:'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    }
}
