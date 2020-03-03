import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-listuser',
  templateUrl: './listuser.component.html',
  styleUrls: ['./listuser.component.scss']
})
export class ListuserComponent implements OnInit {

  constructor(private router:Router) { }

  ngOnInit() {
  }
  new(){
this.router.navigateByUrl('grow/add/users')
  }
}
