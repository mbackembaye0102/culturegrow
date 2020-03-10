import { Component, OnInit } from '@angular/core';
import { AuthService } from '../service/auth.service';

@Component({
  selector: 'app-test',
  templateUrl: './test.component.html',
  styleUrls: ['./test.component.scss']
})
export class TestComponent implements OnInit {
information:any;
  constructor(private authentification :AuthService) { }

  ngOnInit() {
    console.log("test");
    
    this.authentification.infosconnecter().subscribe(
      res=>{console.log(res);
        this.information=res;
      },
      error=>{console.log(error);
      }
    )
    this.authentification.chargementpage();
  }

}
