import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-liste',
  templateUrl: './liste.component.html',
  styleUrls: ['./liste.component.scss']
})
export class ListeComponent implements OnInit {

  constructor() { }
public user=[
  {id: 1,
    username: "yaya",
    Prenom: "El Hadji Yaya",
    Nom: "Ly",
    Telephone: "772652363",
    poste: "Développeur",
  },
  {id: 2,
    username: "mbacke",
    Prenom: "Elhadji Mbacke",
    Nom: "Mbaye",
    Telephone: "786544",
    poste: "Développeur web et mobile",
  },
  {id: 3,
    username: "dabo",
    Prenom: "Adji Anta",
    Nom: "Dabo",
    Telephone: "786544",
    poste1: "Développeur web et mobile&Project Manager",
  }
]
  ngOnInit() {
  }

}
