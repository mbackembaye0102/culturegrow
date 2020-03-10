import { FormGroup, FormControl } from '@angular/forms';
import { AuthService } from './../../../../service/auth.service';
import { Component, OnInit } from '@angular/core';
import { AdminService } from 'src/app/service/admin.service';
import { Teampromo } from 'src/app/model/teampromo.model';

@Component({
  selector: 'app-ajoutcollaborateur',
  templateUrl: './ajoutcollaborateur.component.html',
  styleUrls: ['./ajoutcollaborateur.component.scss']
})
export class AjoutcollaborateurComponent implements OnInit {
  public rien:boolean=true;
  public cache:boolean=true;
  suivant=false;
  public unTab = 2;
  public list=[]
  public nbrFichier = 0;
  public team:Teampromo;
  constructor(private auth:AuthService,private admin:AdminService) { }

  ngOnInit() {
    this.auth.chargementpage()
    this.admin.listeteamgrow().subscribe(
      res=>{
        //console.log(res);
        this.team=res;
        //console.log(this.team);
        //console.log("rien");
        
        
      },
      error=>{
        console.log(error);
        
      }
    )
  }
  user= new FormGroup({
    prenom: new FormControl(''),
    nom: new FormControl(''),
    telephone:new FormControl!(''),
    email:new FormControl(''),
    profil: new FormControl(''),
    teams: new FormControl(''),
    team1: new FormControl('')
  })
    plusteam(){
      this.cache=false;
      this.list.push("a");
      this.nbrFichier++;
    }
    plusteam1(){
      this.list.push("a");
      this.nbrFichier++;
    }
    onAddFile() {
     // this.unTab.push("");
      this.nbrFichier++;
    }
  save(donner){
    console.log(donner);
    this.admin.saveuser(donner.value).subscribe(
      res=>{console.log(res);
      },
      error=>{console.log(error);
      }
    )
  }
}
