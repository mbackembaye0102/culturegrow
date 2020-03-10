import { AuthService } from './../../../../service/auth.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-listeteam',
  templateUrl: './listeteam.component.html',
  styleUrls: ['./listeteam.component.scss']
})
export class ListeteamComponent implements OnInit {

  constructor(private auth:AuthService) { }

  ngOnInit() {
    this.auth.chargementpage()
  }


}
