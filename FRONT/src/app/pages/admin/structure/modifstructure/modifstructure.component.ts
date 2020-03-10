import { AuthService } from './../../../../service/auth.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-modifstructure',
  templateUrl: './modifstructure.component.html',
  styleUrls: ['./modifstructure.component.scss']
})
export class ModifstructureComponent implements OnInit {

  constructor(private auth:AuthService) { }

  ngOnInit() {
    this.auth.chargementpage()
  }

}
