import { StructureService } from '../structure.service';
import { Component, OnInit } from '@angular/core';
import {Structuremodel} from '../../model/structuremodelmodel.'

@Component({
  selector: 'app-liststructure',
  templateUrl: './liststructure.component.html',
  styleUrls: ['./liststructure.component.scss']
})
export class ListstructureComponent implements OnInit {
  public structuremodel:any;
  constructor(private structureService: StructureService) { }
  
  ngOnInit() {
    this.hello();
  }
  hello(){
    this.structureService.liststructure().subscribe(
      res=>{
        console.log(res);
        this.structuremodel=res;
        
      },
      error=>{console.log(error);}
      
    )
  }
}
