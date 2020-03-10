import { AuthService } from './../../../../service/auth.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-modifcollaborateur',
  templateUrl: './modifcollaborateur.component.html',
  styleUrls: ['./modifcollaborateur.component.scss']
})
export class ModifcollaborateurComponent implements OnInit {

  constructor(private auth:AuthService) { }

  ngOnInit() {
    this.auth.chargementpage()
  }


}
