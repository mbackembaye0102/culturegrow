import { AuthService } from './service/auth.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit{
  title = 'TransfertArgentAngular';
  good=false;
  constructor(private auth:AuthService){}
  ngOnInit(){
    this.auth.chargementpage();    
  }
}
