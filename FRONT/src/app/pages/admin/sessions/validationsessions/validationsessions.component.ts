import { AdminService } from './../../../../service/admin.service';
import { ActivatedRoute } from '@angular/router';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-validationsessions',
  templateUrl: './validationsessions.component.html',
  styleUrls: ['./validationsessions.component.scss']
})
export class ValidationsessionsComponent implements OnInit {
  public id:any;
  constructor(private admin:AdminService,private router:ActivatedRoute) { }

  ngOnInit() {
    this.id=this.router.snapshot.params['id'];
    console.log(this.id);
  }

}
