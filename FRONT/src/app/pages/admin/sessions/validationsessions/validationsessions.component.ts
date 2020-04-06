import { AdminService } from './../../../../service/admin.service';
import { ActivatedRoute } from '@angular/router';
import { Component, OnInit } from '@angular/core';
import { FormGroup, FormControl } from '@angular/forms';

@Component({
  selector: 'app-validationsessions',
  templateUrl: './validationsessions.component.html',
  styleUrls: ['./validationsessions.component.scss']
})
export class ValidationsessionsComponent implements OnInit {
  public id:any;
  public next=false;
  public list=[""];
  public rien=true;
  public teams=[
    {id:1,nom:'Tech&Digital'},{id:1,nom:'Teeam Crea'},{id:1,nom:'Team Bisness'},
  ];
  public team:any;
  public nbrFichier=0;
  public nbrTeam=0;
  public all:any;
  constructor(private admin:AdminService,private router:ActivatedRoute) { }

  ngOnInit() {
    this.id=this.router.snapshot.params['id'];
    console.log(this.id);
    
    let a={id:this.id}
    this.admin.oneteamstructure(a).subscribe(
      res=>{
        this.team=res.body;
      },error=>{
        console.log(error);
        
      }
    )
  }
  addsession= new FormGroup({
    date: new FormControl(''),
    choix0: new FormControl(''),
    taille: new FormControl(null),
    all:new FormControl(''),
    structure:new FormControl(''),
  })
  save(donner){
    console.log(this.nbrFichier);
    
    this.addsession.value.taille=this.nbrTeam;
    this.addsession.value.structure=this.id;
    if (this.all==true) {
      this.addsession.value.all="good";
    }
    else if(this.all==false){
      this.addsession.value.all="bad";
    }
    console.log(donner);
    this.admin.savesession(donner).subscribe(
      res=>{
        console.log(res.body);
        
      },
      error=>{
        console.log(error);
        
      }
    )
    
  }
  allteam(){
    this.nbrTeam=null;
    //let date=this.addsession.value.date;
    this.all=true;
    console.log(this.addsession.value); 
    this.next=false;
  }
  badchoix(){
    this.nbrFichier=0;
   // let date=this.addsession.value.date;
   this.all=false;
   // this.addsession.value.date=date;
    this.list=[""];
    this.next=true;
  }
  allteamandteams(){
    this.nbrTeam=0;
  //  let date=this.addsession.value.date;
  this.all=true;
   // this.addsession.value.date=date;
    this.list=[""];
    this.nbrFichier=0;
    this.next=true;
  }
  plusteam1(){
    this.list.push("a");
    console.log(this.addsession.value);
    let b=this.nbrFichier+1;
    let a="choix"+b;
    console.log(a);
    console.log(typeof a);
    this.nbrTeam++;
    this.addsession.addControl(a,new FormControl(''));
    console.log(this.addsession.value);
    if (this.team.length==(this.list.length)) {
      
      this.nbrFichier=null;
    }
    else{
      this.nbrFichier++;
    }
    
  }
}
