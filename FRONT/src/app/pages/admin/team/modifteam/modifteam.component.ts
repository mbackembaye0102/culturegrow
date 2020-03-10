import { AuthService } from './../../../../service/auth.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-modifteam',
  templateUrl: './modifteam.component.html',
  styleUrls: ['./modifteam.component.scss']
})
export class ModifteamComponent implements OnInit {

  constructor(private auth:AuthService) { }

  ngOnInit() {
    this.auth.chargementpage()
  }


}
