import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { AdminService } from 'src/app/service/admin.service';
import { FormGroup, FormControl } from '@angular/forms';

@Component({
  selector: 'app-evaluationteam',
  templateUrl: './evaluationteam.component.html',
  styleUrls: ['./evaluationteam.component.scss']
})
export class EvaluationteamComponent implements OnInit {
  public id:any;
  public user:any;
  public barrem=
  [
    {id:1,valeur:1},{id:2,valeur:2},{id:3,valeur:3},{id:4,valeur:4},{id:5,valeur:5},
  ]
  public resultat=
  [
    {id:0,libelle:'perseverance',valeur:null,style:'#FF4080'},
    {id:1,libelle:'confiance',valeur:null,style:'#FF4080'},
    {id:2,libelle:'collaboration',valeur:null,style:'#FF4080'},
    {id:3,libelle:'autonomie',valeur:null,style:'#FF4080'},
    {id:4,libelle:'problemsolving',valeur:null,style:'#FF4080'},
    {id:5,libelle:'transmission',valeur:null,style:'#FF4080'},
    {id:6,libelle:'performance',valeur:null,style:'#FF4080'},
  ];
  public good=false;
  constructor(private activeroute:ActivatedRoute,private admin:AdminService) { }

  ngOnInit() {
    this.admin.titrepage="EVALUATION";
    this.id=this.activeroute.snapshot.params['id'];
    console.log(this.id);
    let a={id:this.id}
    console.log(a);
    
    this.admin.userteamevaluation(a).subscribe(
      res=>{console.log(res);
        this.user=res.body;
        console.log(this.user);
        
      },
      error=>{console.log(error);
      }
    )
  }
  reponse(partie,valeur){
    console.log(partie);
    console.log(valeur);
    let id=partie+valeur;
    let a= document.getElementById(id)
    let rien=this.resultat.find(r=>r.libelle==partie);
    let value=this.resultat[rien.id].valeur;
    console.log(this.resultat);
    if (value==null) {
      this.resultat[rien.id].valeur=valeur;
      console.log(this.resultat);
      
      a.style.color=rien.style;
    }
    else if(value!=null){
      let lastid=partie+value
      document.getElementById(lastid).style.color="black";
      this.resultat[rien.id].valeur=valeur;
      console.log(this.resultat);
      a.style.color=rien.style;
    }
  }
  validationdonner(){
    console.log(this.resultat);
    let a=this.resultat.find(t=>t.valeur==null);
    if (a) {
      alert("Veuillez remplir tous les champs")
    }
    else{
      alert("bakhe")
      for (let index = 0; index < this.resultat.length; index++) {
        //const element = array[index];
        this.evaluation.get(this.resultat[index].libelle).setValue(this.resultat[index].valeur)
      }
      console.log(this.evaluation.value);
      this.evaluation.get('team').setValue(this.id);
      console.log(this.evaluation.value);
      this.admin.saveevaluation(this.evaluation.value).subscribe(
        res=>{console.log(res);
        },
        error=>{console.log(error);
        }
      )
      
    }
  }
  evaluation=new FormGroup({
    perseverance:new FormControl(''),
    confiance:new FormControl(''),
    collaboration:new FormControl(''),
    autonomie:new FormControl(''),
    problemsolving:new FormControl(''),
    transmission:new FormControl(''),
    performance:new FormControl(''),
    evaluer:new FormControl(''),
    team:new FormControl('')
  })
}
