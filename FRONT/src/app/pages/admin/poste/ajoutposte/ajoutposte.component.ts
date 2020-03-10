import { AuthService } from './../../../../service/auth.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-ajoutposte',
  templateUrl: './ajoutposte.component.html',
  styleUrls: ['./ajoutposte.component.scss']
})
export class AjoutposteComponent implements OnInit {

  constructor(private auth:AuthService) { }

  ngOnInit() {
    this.auth.chargementpage()
  }


}
