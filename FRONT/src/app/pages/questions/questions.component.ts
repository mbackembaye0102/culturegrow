import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-questions',
  templateUrl: './questions.component.html',
  styleUrls: ['./questions.component.scss']
})
export class QuestionsComponent implements OnInit {
  public barrem=
  [
    {id:1,valeur:1},{id:2,valeur:2},{id:3,valeur:3},{id:4,valeur:4},{id:5,valeur:5},
    {id:6,valeur:6},{id:7,valeur:7},{id:8,valeur:8},{id:9,valeur:9},{id:10,valeur:10}
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
  constructor() { }

  ngOnInit() {
  }
  reponse(partie,valeur){
    console.log(partie);
    console.log(valeur);
    let id=partie+valeur;
    let a= document.getElementById(id)
    let rien=this.resultat.find(r=>r.libelle==partie);
    let value=this.resultat[rien.id].valeur;
    if (value==null) {
      this.resultat[rien.id].valeur=valeur;
      a.style.color=rien.style;
    }
    else if(value!=null){
      let lastid=partie+value
      document.getElementById(lastid).style.color="black";
      this.resultat[rien.id].valeur=valeur;
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
    }
  }
}
