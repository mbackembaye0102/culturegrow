import { AdminService } from './../../../../service/admin.service';
import { ActivatedRoute, Router } from '@angular/router';
import { Component, OnInit,ViewChild } from '@angular/core';
import {MatSort} from '@angular/material/sort';
import {MatTableDataSource} from '@angular/material/table';
import {MatPaginator} from '@angular/material/paginator';
import { FormGroup, FormControl } from '@angular/forms';
import { MatDialog } from '@angular/material/dialog';
import { ModaldateComponent } from '../modaldate/modaldate.component';


@Component({
  selector: 'app-detailcollaborateur',
  templateUrl: './detailcollaborateur.component.html',
  styleUrls: ['./detailcollaborateur.component.scss']
})
export class DetailcollaborateurComponent implements OnInit {
  public id:string;
  public user:any;
  public iam:any;
  public rien:any;
  public rien1=[];
  displayedColumns: string[] = [ 'select','position','date','type','detail'];
  public dataSource:any;
  public goodselect:boolean=false;
  public diag=false;
  public dataselect=[];
  public myChart:any;
  chart:any;
  public data:any;
  public date:any;
  public perseverance:any;
  public confiance:any;
  public collaboration:any;
  public autonomie:any;
  public problemsolving:any;
  public transmission:any;
  public performance:any;

  public data1:any;
  public date1:any;
  public perseverance1:any;
  public confiance1:any;
  public collaboration1:any;
  public autonomie1:any;
  public problemsolving1:any;
  public transmission1:any;
  public performance1:any;
 // public modelsata={}
 public perfectpersodate=false;
  public taille=0;
  @ViewChild(MatSort) sort: MatSort;
  @ViewChild(MatPaginator) paginator: MatPaginator;
  public barChartOptionsperseverance = {
    scaleShowVerticalLines: false,
    responsive: true,
    scales: {
      yAxes: [{
          ticks: {
              suggestedMin: 0,
              suggestedMax: 5,
              stepSize: 1
          }
      }]
    }
  };
  public barChartLabelsperseverance:any;
  public barChartTypeperseverance :any;
  public barChartLegendperseverance:any;
  public barChartDataperseverance;
  public barChartOptionsconfiance = {
    scaleShowVerticalLines: false,
    responsive: true,
    scales: {
      yAxes: [{
          ticks: {
              suggestedMin: 0,
              suggestedMax: 5,
              stepSize: 1
          }
      }]
    }
  };
  public barChartLabelsconfiance:any;
  public barChartTypeconfiance :any;
  public barChartLegendconfiance:any;
  public barChartDataconfiance;

  public barChartOptionscollaboration = {
    scaleShowVerticalLines: false,
    responsive: true,
    scales: {
      yAxes: [{
          ticks: {
              suggestedMin: 0,
              suggestedMax: 5,
              stepSize: 1
          }
      }]
    }
  };
  public barChartLabelscollaboration:any;
  public barChartTypecollaboration :any;
  public barChartLegendcollaboration:any;
  public barChartDatacollaboration;

  public barChartOptionsautonomie = {
    scaleShowVerticalLines: false,
    responsive: true,
    scales: {
      yAxes: [{
          ticks: {
              suggestedMin: 0,
              suggestedMax: 5,
              stepSize: 1
          }
      }]
    }
  };
  public barChartLabelsautonomie:any;
  public barChartTypeautonomie :any;
  public barChartLegendautonomie:any;
  public barChartDataautonomie;

  public barChartOptionsproblemsolving = {
    scaleShowVerticalLines: false,
    responsive: true,
    scales: {
      yAxes: [{
          ticks: {
              suggestedMin: 0,
              suggestedMax: 5,
              stepSize: 1
          }
      }]
    }
  };
  public barChartLabelsproblemsolving:any;
  public barChartTypeproblemsolving :any;
  public barChartLegendproblemsolving:any;
  public barChartDataproblemsolving;

  public barChartOptionstransmission = {
    scaleShowVerticalLines: false,
    responsive: true,
    scales: {
      yAxes: [{
          ticks: {
              suggestedMin: 0,
              suggestedMax: 5,
              stepSize: 1
          }
      }]
    }
  };
  public barChartLabelstransmission:any;
  public barChartTypetransmission :any;
  public barChartLegendtransmission:any;
  public barChartDatatransmission;

  public barChartOptionsperformance = {
    scaleShowVerticalLines: false,
    responsive: true,
    scales: {
      yAxes: [{
          ticks: {
              suggestedMin: 0,
              suggestedMax: 5,
              stepSize: 1
          }
      }]
    }
  };
  public barChartLabelsperformance:any;
  public barChartTypeperformance :any;
  public barChartLegendperformance:any;
  public barChartDataperformance;
  public persodate={nombre:0};

  public barChartOptionsperseverance1 = {
    scaleShowVerticalLines: false,
    responsive: true,
    scales: {
      yAxes: [{
          ticks: {
              suggestedMin: 0,
              suggestedMax: 5,
              stepSize: 1
          }
      }]
    }
  };
  public barChartLabelsperseverance1:any;
  public barChartTypeperseverance1 :any;
  public barChartLegendperseverance1:any;
  public barChartDataperseverance1;
  public barChartOptionsconfiance1 = {
    scaleShowVerticalLines: false,
    responsive: true,
    scales: {
      yAxes: [{
          ticks: {
              suggestedMin: 0,
              suggestedMax: 5,
              stepSize: 1
          }
      }]
    }
  };
  public barChartLabelsconfiance1:any;
  public barChartTypeconfiance1 :any;
  public barChartLegendconfiance1:any;
  public barChartDataconfiance1;

  public barChartOptionscollaboration1 = {
    scaleShowVerticalLines: false,
    responsive: true,
    scales: {
      yAxes: [{
          ticks: {
              suggestedMin: 0,
              suggestedMax: 5,
              stepSize: 1
          }
      }]
    }
  };
  public barChartLabelscollaboration1:any;
  public barChartTypecollaboration1 :any;
  public barChartLegendcollaboration1:any;
  public barChartDatacollaboration1;

  public barChartOptionsautonomie1 = {
    scaleShowVerticalLines: false,
    responsive: true,
    scales: {
      yAxes: [{
          ticks: {
              suggestedMin: 0,
              suggestedMax: 5,
              stepSize: 1
          }
      }]
    }
  };
  public barChartLabelsautonomie1:any;
  public barChartTypeautonomie1 :any;
  public barChartLegendautonomie1:any;
  public barChartDataautonomie1;

  public barChartOptionsproblemsolving1 = {
    scaleShowVerticalLines: false,
    responsive: true,
    scales: {
      yAxes: [{
          ticks: {
              suggestedMin: 0,
              suggestedMax: 5,
              stepSize: 1
          }
      }]
    }
  };
  public barChartLabelsproblemsolving1:any;
  public barChartTypeproblemsolving1 :any;
  public barChartLegendproblemsolving1:any;
  public barChartDataproblemsolving1;

  public barChartOptionstransmission1 = {
    scaleShowVerticalLines: false,
    responsive: true,
    scales: {
      yAxes: [{
          ticks: {
              suggestedMin: 0,
              suggestedMax: 5,
              stepSize: 1
          }
      }]
    }
  };
  public barChartLabelstransmission1:any;
  public barChartTypetransmission1 :any;
  public barChartLegendtransmission1:any;
  public barChartDatatransmission1;

  public barChartOptionsperformance1 = {
    scaleShowVerticalLines: false,
    responsive: true,
    scales: {
      yAxes: [{
          ticks: {
              suggestedMin: 0,
              suggestedMax: 5,
              stepSize: 1
          }
      }]
    }
  };
  public barChartLabelsperformance1:any;
  public barChartTypeperformance1 :any;
  public barChartLegendperformance1:any;
  public barChartDataperformance1;

  constructor(private activeRoute:ActivatedRoute,public admin:AdminService,private router:Router,public dialog: MatDialog) { }

  ngOnInit() {
    this.admin.titrepage="DETAIL COLLABORATEUR";
    this.id=this.activeRoute.snapshot.params['id'];
    let a={id:this.id};
    this.initialisationchart();
    this.admin.lastevaluation(a).subscribe(
      res=>{
         this.data=res.body;
         this.date=this.data.date;
         this.perseverance=this.data.perseverance;
         this.chartPerseverance();
         this.confiance=this.data.confiance;
         this.chartConfiance();
         this.collaboration=this.data.collaboration;
         this.chartCollaboration();
         this.autonomie=this.data.autonomie;
         this.chartAutonomie();
         this.problemsolving=this.data.problemsolving;
         this.chartProblemsolving();
         this.transmission=this.data.transmission;
         this.chartTransmission();
         this.performance=this.data.performance;
         this.chartPerformance();
      },
      error=>{console.log(error);
      }
    )
    this.admin.detailuser(a).subscribe(
      res=>{
        console.log(res.body);
        this.iam=res.body;
        //console.log(this.iam.teamevaluer);
        this.user=res.body;
      },
      error=>{
        console.log(error);
        
      }
    )
    this.admin.usersession(a).subscribe(
      res=>{
        console.log(res.body);
        this.rien=res.body;
        for (let index = 0; index < this.rien.length; index++) {
         this.rien1.push(this.rien[index]) 
        }
        for (let index = 0; index < this.rien.length; index++) {
            this.rien[index].position=index+1;
            if (this.rien[index].concerner=="good") {
              if (this.rien[index].teams.length>0) {
                this.rien[index].concerner="Evaluation par Team et "+this.rien[index].teams;
              }
              else{
                this.rien[index].concerner="Evaluation par Team";
              }
            }
            else if(this.rien[index].concerner=="bad"){
              this.rien[index].concerner="Evaluation par Team";
            }
          
        }
        this.dataSource=new MatTableDataSource(this.rien);
        this.dataSource.sort = this.sort;
        this.dataSource.paginator = this.paginator;
      },
      error=>{
        console.log(error);
        
      }
    )
  }
  detail(donner){
    console.log(donner);
    this.admin.usersessiondata.idsession=donner;
    this.admin.usersessiondata.iduser=this.id;
    console.log(this.admin.usersessiondata);
    this.router.navigate(["/detailusersession"]);
    
    
  }
  formulare=new FormGroup({
    taille:new FormControl(''),
    date0:new FormControl(''),
    id:new FormControl(''),
  })
  change(statut,date){
    console.log(statut);
    console.log(date);
    this.goodselect=true;
    let alpha=false;
    for (let index = 0; index < this.dataselect.length; index++) {
      if (this.dataselect[index].date==date) {
        this.dataselect[index].statut=statut;
        alpha=true;
      } 
    }
    if (alpha==false) {
      this.dataselect.push({date:date,statut:statut})
    }
    console.log(this.dataselect);

  }
  diagramme(){
    console.log(this.dataselect);
    this.formulare.reset();
    this.taille=0;
    for (let index = 0; index < this.dataselect.length; index++) {
      if (this.dataselect[index].statut==true) {
        this.formulare.get('date'+this.taille).setValue(this.dataselect[index].date);
        this.formulare.get('taille').setValue(this.taille);
        this.taille++;
        this.formulare.addControl('date'+this.taille,new FormControl(''))
      }
    }
    this.formulare.get('id').setValue(this.id);
    console.log(this.formulare.value);
    this.admin.userdata=this.formulare.value;
    
    this.nextr();
  }
  nextr(){
    this.diag=true;
  }
  chartPerseverance(){
    this.barChartLabelsperseverance = this.date;
    this.barChartTypeperseverance = 'bar';
    this.barChartLegendperseverance = true;
    this.barChartDataperseverance = [
     {data: this.perseverance, label: 'Perseverance',backgroundColor: "#FF4080"}
   ];
  }

   chartConfiance(){
    this.barChartLabelsconfiance = this.date;
    this.barChartTypeconfiance = 'bar';
    this.barChartLegendconfiance = true;
    this.barChartDataconfiance = [
     {data: this.confiance, label: 'Confiance',backgroundColor: "#FF4080"}
   ];
  }
 
  chartCollaboration(){
    this.barChartLabelscollaboration = this.date;
    this.barChartTypecollaboration = 'bar';
    this.barChartLegendcollaboration = true;
    this.barChartDatacollaboration = [
     {data: this.collaboration, label: 'Collaboration',backgroundColor: "#FF4080"}
   ];
 }

 chartAutonomie(){
  this.barChartLabelsautonomie = this.date;
  this.barChartTypeautonomie = 'bar';
  this.barChartLegendautonomie = true;
  this.barChartDataautonomie = [
   {data: this.autonomie, label: 'Autonomie',backgroundColor: "#FF4080"}
 ];
 }

 chartProblemsolving(){
  this.barChartLabelsproblemsolving = this.date;
  this.barChartTypeproblemsolving = 'bar';
  this.barChartLegendproblemsolving = true;
  this.barChartDataproblemsolving = [
   {data: this.problemsolving, label: 'problem solving',backgroundColor: "#FF4080"}
 ];
 }

 chartTransmission(){
  this.barChartLabelstransmission = this.date;
  this.barChartTypetransmission = 'bar';
  this.barChartLegendtransmission = true;
  this.barChartDatatransmission = [
   {data: this.transmission, label: 'Transmission',backgroundColor: "#FF4080"}
 ];
 }
 chartPerformance(){
  this.barChartLabelsperformance = this.date;
  this.barChartTypeperformance = 'bar';
  this.barChartLegendperformance = true;
  this.barChartDataperformance = [
   {data: this.performance, label: 'performance',backgroundColor: "#FF4080"}
 ];
  }
  initialisationchart(){
    this.barChartLabelsperseverance = ["2020", "2020", "2020", "2020", "2020", "2020", "2020"];
    this.barChartTypeperseverance = 'bar';
    this.barChartLegendperseverance = true;
    this.barChartDataperseverance = [
     {data: [0, 0, 0, 0, 0, 0, 0], label: 'P',backgroundColor: "#FF4080"}
    ];
  
    this.barChartLabelsconfiance = ["2020", "2020", "2020", "2020", "2020", "2020", "2020"];
    this.barChartTypeconfiance = 'bar';
    this.barChartLegendconfiance = true;
    this.barChartDataconfiance = [
     {data: [0, 0, 0, 0, 0, 0, 0], label: 'P',backgroundColor: "#FF4080"}
    ];
  
    this.barChartLabelscollaboration = ["2020", "2020", "2020", "2020", "2020", "2020", "2020"];
    this.barChartTypecollaboration = 'bar';
    this.barChartLegendcollaboration = true;
    this.barChartDatacollaboration = [
     {data: [0, 0, 0, 0, 0, 0, 0], label: 'P',backgroundColor: "#FF4080"}
    ];
  
    this.barChartLabelsautonomie = ["2020", "2020", "2020", "2020", "2020", "2020", "2020"];
    this.barChartTypeautonomie = 'bar';
    this.barChartLegendautonomie = true;
    this.barChartDataautonomie = [
     {data: [0, 0, 0, 0, 0, 0, 0], label: 'P',backgroundColor: "#FF4080"}
    ];
  
    this.barChartLabelsproblemsolving = ["2020", "2020", "2020", "2020", "2020", "2020", "2020"];
    this.barChartTypeproblemsolving = 'bar';
    this.barChartLegendproblemsolving = true;
    this.barChartDataproblemsolving = [
     {data: [0, 0, 0, 0, 0, 0, 0], label: 'P',backgroundColor: "#FF4080"}
    ];
  
    this.barChartLabelstransmission = ["2020", "2020", "2020", "2020", "2020", "2020", "2020"];
    this.barChartTypetransmission = 'bar';
    this.barChartLegendtransmission = true;
    this.barChartDatatransmission = [
     {data: [0, 0, 0, 0, 0, 0, 0], label: 'P',backgroundColor: "#FF4080"}
    ];
  
    this.barChartLabelsperformance = ["2020", "2020", "2020", "2020", "2020", "2020", "2020"];
    this.barChartTypeperformance = 'bar';
    this.barChartLegendperformance = true;
    this.barChartDataperformance = [
     {data: [0, 0, 0, 0, 0, 0, 0], label: 'P',backgroundColor: "#FF4080"}
    ];
  }
  choicedate(){
    
      const dialogRef = this.dialog.open(ModaldateComponent, {
      });
  
      dialogRef.afterClosed().subscribe(result => {
        console.log('The dialog was closed');
        console.log(this.admin.lesdate);
        
        for (let index = 0; index < this.admin.lesdate.length; index++) {
            if (this.admin.lesdate[index].etat==true) {

              let r=this.persodate.nombre;
              let b="date"+r;
              this.persodate[b]=this.admin.lesdate[index].date;
              this.persodate.nombre++;
            }
        }
        console.log(this.persodate);
        this.perfectpersodate=true;
        this.admin.persoevaluation(this.persodate).subscribe(
          res=>{console.log(res);
            this.data1=res.body;
            this.date1=this.data1.date1;
            this.perseverance1=this.data1.perseverance1;
            this.chartPerseverance1();
            this.confiance1=this.data1.confiance1;
            this.chartConfiance1();
            this.collaboration1=this.data1.collaboration1;
            this.chartCollaboration1();
            this.autonomie1=this.data1.autonomie1;
            this.chartAutonomie1();
            this.problemsolving1=this.data1.problemsolving1;
            this.chartProblemsolving1();
            this.transmission1=this.data1.transmission1;
            this.chartTransmission1();
            this.performance1=this.data1.performance1;
            this.chartPerformance1();
          },
          error=>{console.log(error);
          }
        )
      });
    
  
  }
  initialisationchart1(){
    this.barChartLabelsperseverance1 = ["2020", "2020", "2020", "2020", "2020", "2020", "2020"];
    this.barChartTypeperseverance1 = 'bar';
    this.barChartLegendperseverance1 = true;
    this.barChartDataperseverance1 = [
     {data: [0, 0, 0, 0, 0, 0, 0], label: 'P',backgroundColor: "#FF4080"}
    ];
  
    this.barChartLabelsconfiance1 = ["2020", "2020", "2020", "2020", "2020", "2020", "2020"];
    this.barChartTypeconfiance1 = 'bar';
    this.barChartLegendconfiance1 = true;
    this.barChartDataconfiance1 = [
     {data: [0, 0, 0, 0, 0, 0, 0], label: 'P',backgroundColor: "#FF4080"}
    ];
  
    this.barChartLabelscollaboration1 = ["2020", "2020", "2020", "2020", "2020", "2020", "2020"];
    this.barChartTypecollaboration1 = 'bar';
    this.barChartLegendcollaboration1 = true;
    this.barChartDatacollaboration1 = [
     {data: [0, 0, 0, 0, 0, 0, 0], label: 'P',backgroundColor: "#FF4080"}
    ];
  
    this.barChartLabelsautonomie1 = ["2020", "2020", "2020", "2020", "2020", "2020", "2020"];
    this.barChartTypeautonomie1 = 'bar';
    this.barChartLegendautonomie1 = true;
    this.barChartDataautonomie1 = [
     {data: [0, 0, 0, 0, 0, 0, 0], label: 'P',backgroundColor: "#FF4080"}
    ];
  
    this.barChartLabelsproblemsolving1 = ["2020", "2020", "2020", "2020", "2020", "2020", "2020"];
    this.barChartTypeproblemsolving1 = 'bar';
    this.barChartLegendproblemsolving1 = true;
    this.barChartDataproblemsolving1 = [
     {data: [0, 0, 0, 0, 0, 0, 0], label: 'P',backgroundColor: "#FF4080"}
    ];
  
    this.barChartLabelstransmission1 = ["2020", "2020", "2020", "2020", "2020", "2020", "2020"];
    this.barChartTypetransmission1 = 'bar';
    this.barChartLegendtransmission1 = true;
    this.barChartDatatransmission1 = [
     {data: [0, 0, 0, 0, 0, 0, 0], label: 'P',backgroundColor: "#FF4080"}
    ];
  
    this.barChartLabelsperformance1 = ["2020", "2020", "2020", "2020", "2020", "2020", "2020"];
    this.barChartTypeperformance1 = 'bar';
    this.barChartLegendperformance1 = true;
    this.barChartDataperformance1 = [
     {data: [0, 0, 0, 0, 0, 0, 0], label: 'P',backgroundColor: "#FF4080"}
    ];
  }

  chartPerseverance1(){
    this.barChartLabelsperseverance1 = this.date1;
    this.barChartTypeperseverance1 = 'bar';
    this.barChartLegendperseverance1 = true;
    this.barChartDataperseverance1 = [
     {data: this.perseverance1, label: 'Perseverance',backgroundColor: "#FF4080"}
   ];
  }

   chartConfiance1(){
    this.barChartLabelsconfiance1 = this.date1;
    this.barChartTypeconfiance1 = 'bar';
    this.barChartLegendconfiance1 = true;
    this.barChartDataconfiance1 = [
     {data: this.confiance1, label: 'Confiance',backgroundColor: "#FF4080"}
   ];
  }
 
  chartCollaboration1(){
    this.barChartLabelscollaboration1 = this.date1;
    this.barChartTypecollaboration1 = 'bar';
    this.barChartLegendcollaboration1 = true;
    this.barChartDatacollaboration1 = [
     {data: this.collaboration1, label: 'Collaboration',backgroundColor: "#FF4080"}
   ];
 }

 chartAutonomie1(){
  this.barChartLabelsautonomie1 = this.date1;
  this.barChartTypeautonomie1 = 'bar';
  this.barChartLegendautonomie1 = true;
  this.barChartDataautonomie1 = [
   {data: this.autonomie1, label: 'Autonomie',backgroundColor: "#FF4080"}
 ];
 }

 chartProblemsolving1(){
  this.barChartLabelsproblemsolving1 = this.date1;
  this.barChartTypeproblemsolving1 = 'bar';
  this.barChartLegendproblemsolving1 = true;
  this.barChartDataproblemsolving1 = [
   {data: this.problemsolving1, label: 'problem solving',backgroundColor: "#FF4080"}
 ];
 }

 chartTransmission1(){
  this.barChartLabelstransmission1 = this.date1;
  this.barChartTypetransmission1 = 'bar';
  this.barChartLegendtransmission1 = true;
  this.barChartDatatransmission1 = [
   {data: this.transmission1, label: 'Transmission',backgroundColor: "#FF4080"}
 ];
 }
 chartPerformance1(){
  this.barChartLabelsperformance1 = this.date1;
  this.barChartTypeperformance1 = 'bar';
  this.barChartLegendperformance1 = true;
  this.barChartDataperformance1 = [
   {data: this.performance1, label: 'performance',backgroundColor: "#FF4080"}
 ];
  }
}
