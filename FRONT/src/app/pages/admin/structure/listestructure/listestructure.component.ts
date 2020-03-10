import { AuthService } from './../../../../service/auth.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-listestructure',
  templateUrl: './listestructure.component.html',
  styleUrls: ['./listestructure.component.scss']
})
export class ListestructureComponent implements OnInit {

  constructor(private auth:AuthService) { }

  ngOnInit() {
    this.auth.chargementpage()
  }

}
