import { Router } from '@angular/router';
import { AdminService } from './../../service/admin.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.scss']
})
export class HeaderComponent implements OnInit {

  constructor(public admin:AdminService,private router:Router) { }
  public titre:string;
  ngOnInit() {
    this.titre=this.admin.titrepage;
  }

}
