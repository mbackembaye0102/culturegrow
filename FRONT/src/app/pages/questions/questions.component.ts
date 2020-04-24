import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-questions',
  templateUrl: './questions.component.html',
  styleUrls: ['./questions.component.scss']
})
export class QuestionsComponent implements OnInit {
  public barrem=
  [
    {id:1,valeur:1},{id:2,valeur:2},{id:3,valeur:3},{id:4,valeur:4},{id:5,valeur:5}
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
    for (let index = 1; index <= valeur; index++) {
      let a=partie+index;
      document.getElementById(a).setAttribute('src','assets/star1.png');
      
    }
    for (let index = 0; valeur < 5; index++) {
      valeur++
      let a=partie+valeur;
      document.getElementById(a).setAttribute('src','assets/star.png');
      
    }
    // let id=partie+valeur;
    // let a= document.getElementById(id)
    // let rien=this.resultat.find(r=>r.libelle==partie);
    // let value=this.resultat[rien.id].valeur;
    // if (value==null) {
    //   this.resultat[rien.id].valeur=valeur;
    //   a.style.color=rien.style;
    // }
    // else if(value!=null){
    //   let lastid=partie+value
    //   document.getElementById(lastid).style.color="black";
    //   this.resultat[rien.id].valeur=valeur;
    //   a.style.color=rien.style;
    // }
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
