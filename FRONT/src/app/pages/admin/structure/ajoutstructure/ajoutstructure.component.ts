import { Router } from '@angular/router';
import { FormGroup, FormControl } from '@angular/forms';
import { AuthService } from './../../../../service/auth.service';
import { Component, OnInit } from '@angular/core';
import { AdminService } from 'src/app/service/admin.service';

@Component({
  selector: 'app-ajoutstructure',
  templateUrl: './ajoutstructure.component.html',
  styleUrls: ['./ajoutstructure.component.scss']
})
export class AjoutstructureComponent implements OnInit {
  constructor(private asmin:AdminService,private auth:AuthService,private router:Router) { }

  ngOnInit() {
  }
  structure= new FormGroup({
    nom:new FormControl('')
  })
  save(donner){
console.log(donner);
this.asmin.addstructure(donner).subscribe(
  res=>{
    console.log(res);
    this.router.navigate(['/structure'])
    
  },
  error=>{console.log(error);
  }
)
  }
}
