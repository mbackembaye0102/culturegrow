import { Router } from '@angular/router';
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
  public taille=1;
  public team:Teampromo;
  public job:Teampromo;
  constructor(private auth:AuthService,private admin:AdminService,private router:Router) { }

  ngOnInit() {
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
     // this.unTab.push("");
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
}
