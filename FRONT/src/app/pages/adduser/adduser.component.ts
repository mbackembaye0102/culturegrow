import { FormGroup, FormControl } from '@angular/forms';
import {Router } from '@angular/router';
import { Component, OnInit } from '@angular/core';
import { Teampromo } from 'src/app/model/teampromo.model';
import { AdminService } from 'src/app/service/admin.service';
import { AuthService } from 'src/app/service/auth.service';

@Component({
  selector: 'app-adduser',
  templateUrl: './adduser.component.html',
  styleUrls: ['./adduser.component.scss']
})
export class AdduserComponent implements OnInit {
  url={addcollaborateur:'/collaborateur/add',addteam:'/team/add'}
  public rien:boolean=true;
  public cache:boolean=true;
  suivant=false;
  public unTab = 2;
  public list=[]
  public nbrFichier = 0;
  public taille=1;
  public team:Teampromo;
  public job:Teampromo;
  public addgrow:boolean=false;
  public addteam:boolean=false;
  constructor(private router: Router,private auth:AuthService,private admin:AdminService) { }

  ngOnInit() {
    console.log(this.admin.idteam);
    
    let a=this.router.url;
    console.log(a);
    
    if (a==this.url.addcollaborateur) {
      this.addgrow=true;
          this.admin.listeteamgrow().subscribe(
      res=>{
        this.team=res;
      },
      error=>{
        console.log(error);
      }
    )
    this.admin.listepostegrow().subscribe(
      res=>{
        this.job=res;
      },
      error=>{
        console.log(error);
      }
    )
    }
    else if(a==this.url.addteam){
      this.addteam=true;
    }

  }
  user= new FormGroup({
    prenom: new FormControl(''),
    nom: new FormControl(''),
    telephone:new FormControl!(''),
    email:new FormControl(''),
    profil: new FormControl(''),
    team1: new FormControl(''),
    team2: new FormControl(''),
    taille: new FormControl(''),
    poste: new FormControl('')
  });
    plusteam(){
      this.cache=false;
      this.list.push("a");
      this.nbrFichier++;
      this.taille++;
    }
    plusteam1(){
      this.list.push("a");
      this.nbrFichier++;
      this.taille++;
    }
    onAddFile() {
      this.nbrFichier++;
    }
  save(donner){
    console.log(donner);
    console.log(this.taille);
    donner.taille=this.taille;
    console.log(donner);
    this.admin.saveuser(donner).subscribe(
      res=>{console.log(res);
        this.router.navigate(['/collaborateur'])

      },
      error=>{console.log(error);
      }
    )
  }
  userteam=new FormGroup({
    prenom: new FormControl(''),
    nom: new FormControl(''),
    telephone: new FormControl(''),
    email: new FormControl(''),
    poste: new FormControl(''),
    id: new FormControl(''),
  })
  saveuserteam(data){
    data.id=this.admin.idteam;
    this.admin.saveuserteam(data).subscribe(
      res=>{console.log(res);
        this.router.navigate(['/team/'+data.id])
      },
      error=>{console.log(error);
      }
    )
    console.log(data);
    
  }
}
