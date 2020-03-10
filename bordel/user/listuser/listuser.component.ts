import { StructureService } from '../../structuress/structure.service';
import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-listuser',
  templateUrl: './listuser.component.html',
  styleUrls: ['./listuser.component.scss']
})
export class ListuserComponent implements OnInit {
user:any;
  constructor(private router:Router,private monService:StructureService) { }

  ngOnInit() {
this.monService.listuser().subscribe(
  res=>{console.log(res);
    this.user=res;
  },
  error=>{console.log(error);
  }
)
  }
  new(){
this.router.navigateByUrl('grow/add/users')
  }
}
