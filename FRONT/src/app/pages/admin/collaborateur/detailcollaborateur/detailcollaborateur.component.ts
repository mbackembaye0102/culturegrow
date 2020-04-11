import { AdminService } from './../../../../service/admin.service';
import { ActivatedRoute, Router } from '@angular/router';
import { Component, OnInit,ViewChild } from '@angular/core';
import {MatSort} from '@angular/material/sort';
import {MatTableDataSource} from '@angular/material/table';
import {MatPaginator} from '@angular/material/paginator';
import { FormGroup, FormControl } from '@angular/forms';

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
 // public modelsata={}
  public taille=0;
  @ViewChild(MatSort) sort: MatSort;
  @ViewChild(MatPaginator) paginator: MatPaginator;
  constructor(private activeRoute:ActivatedRoute,private admin:AdminService,private router:Router) { }

  ngOnInit() {

    this.id=this.activeRoute.snapshot.params['id'];
    let a={id:this.id};
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
      //  console.log(res);
        
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
        console.log(this.rien);
        console.log(this.rien1);
        
        
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
    date0:new FormControl('')
  })
  change(statut,date){
    console.log(statut);
    console.log(date);
    this.goodselect=true;
    if (statut) {
      this.dataselect.push({date:date,statut:statut})
    }
    else{
      for (let index = 0; index < this.dataselect.length; index++) {
        if (this.dataselect[index].date==date) {
          this.dataselect[index].statut=statut
        }
        
      }
    //  console.log(this.dataselect);
      
    }
    console.log(this.dataselect);

  }
  diagramme(){
    console.log(this.dataselect);
    for (let index = 0; index < this.dataselect.length; index++) {
      if (this.dataselect[index].statut==true) {
        this.formulare.get('date'+this.taille).setValue(this.dataselect[index].date);
        this.formulare.get('taille').setValue(this.taille);
        this.taille++;
        this.formulare.addControl('date'+this.taille,new FormControl(''))
      }
    }
    console.log(this.formulare.value);
    
    this.nextr();
  }
  nextr(){
    this.diag=true;
  }

}
