import { AuthService } from './../../../../service/auth.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-ajoutstructure',
  templateUrl: './ajoutstructure.component.html',
  styleUrls: ['./ajoutstructure.component.scss']
})
export class AjoutstructureComponent implements OnInit {

  constructor(private auth:AuthService) { }

  ngOnInit() {
    this.auth.chargementpage()
  }

}
