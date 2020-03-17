import { AuthService } from './../../../../service/auth.service';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-listeteam',
  templateUrl: './listeteam.component.html',
  styleUrls: ['./listeteam.component.scss']
})
export class ListeteamComponent implements OnInit {

  constructor(private auth:AuthService,private route:ActivatedRoute) { }
  public id:any;
  ngOnInit() {
    //this.auth.chargementpage()
    this.id=this.route.snapshot.params['id'];
    console.log(this.id);
    
  }


}
