import { AuthService } from './../../../service/auth.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss']
})
export class HomeComponent implements OnInit {

  constructor(private auth:AuthService) { }

  ngOnInit() {
    this.auth.chargementpage()
    console.log(this.diagne);
    
  }
  diagne=[
  {
    'idK':1,
    'commentaire':'Merciiiiiiiiiii',
    'nombeneficiare':'Ndioba DIAGNE',
    'datekudo':123546546,
    'utilisateur':{
      'nom':'Mansour DRAME'
    }
  },

  {
    'idK':2,
    'commentaire':'Merci pour le coashing',
    'nombeneficiare':'Mansour DRAME',
    'datekudo':5498744654464,
    'utilisateur':{
      'nom':'Ndioba DIAGNE'
    }
  }
  ]

}
