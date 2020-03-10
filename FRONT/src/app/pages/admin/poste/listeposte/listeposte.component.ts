import { AuthService } from './../../../../service/auth.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-listeposte',
  templateUrl: './listeposte.component.html',
  styleUrls: ['./listeposte.component.scss']
})
export class ListeposteComponent implements OnInit {

  constructor(private auth:AuthService) { }

  ngOnInit() {
    this.auth.chargementpage()
  }


}
