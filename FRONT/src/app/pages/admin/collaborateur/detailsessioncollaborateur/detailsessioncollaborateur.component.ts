import { Component, OnInit,ViewChild } from '@angular/core';
import { AdminService } from 'src/app/service/admin.service';
import {MatPaginator} from '@angular/material/paginator';
import {MatTableDataSource} from '@angular/material/table';
import { FormGroup, FormControl } from '@angular/forms';
@Component({
  selector: 'app-detailsessioncollaborateur',
  templateUrl: './detailsessioncollaborateur.component.html',
  styleUrls: ['./detailsessioncollaborateur.component.scss']
})
export class DetailsessioncollaborateurComponent implements OnInit {
  displayedColumns: string[] = ['evaluer', 'evaluateur', 'autonomie', 'collaboration', 'confiance', 'performance', 'perseverance','problemsolving','transmission'];
  dataSource = new MatTableDataSource();
 // selection = new SelectionModel(true, []);

  @ViewChild(MatPaginator) paginator: MatPaginator;
  public teamevaluation:any;
  public user:any;
  constructor(private admin:AdminService) { }

  ngOnInit() {
    this.admin.titrepage="DETAIL SESSION COLLABORATEUR";
    console.log(this.admin.usersessiondata);
    this.admin.usersessionteam(this.admin.usersessiondata).subscribe(
      res=>{console.log(res.body);
        this.teamevaluation=res.body;
        this.teamevaluation=this.teamevaluation.team
        //console.log();
        
      },
      error=>{
        console.log(error);
        
      }
    )
    
    
  }
  detailteam=new FormGroup({
    team:new FormControl('')
  })
  applyFilter(event: Event) {
    const filterValue = (event.target as HTMLInputElement).value;
    this.dataSource.filter = filterValue.trim().toLowerCase();
  }
  changement(donner){
    console.log(donner);
    this.admin.usersessiondata.team=donner;
    this.admin.userdetailsessionevaluation(this.admin.usersessiondata).subscribe(
      res=>{
        console.log(this.admin.usersessiondata);
        console.log(res.body);
        this.user=res.body;
        this.dataSource = new MatTableDataSource(this.user);
        this.dataSource.paginator = this.paginator;
       // selection = new SelectionModel<PeriodicElement>(true, []);
      },
      error=>{console.log(error);
      }
    )
  }
  //selection = new SelectionModel<PeriodicElement>(true, []);

  /** Whether the number of selected elements matches the total number of rows. */
  // isAllSelected() {
  //   const numSelected = this.selection.selected.length;
  //   const numRows = this.dataSource.data.length;
  //   return numSelected === numRows;
  // }

  // /** Selects all rows if they are not all selected; otherwise clear selection. */
  // masterToggle() {
  //   this.isAllSelected() ?
  //       this.selection.clear() :
  //       this.dataSource.data.forEach(row => this.selection.select(row));
  // }

  // /** The label for the checkbox on the passed row */
  // checkboxLabel(row?: this.user): string {
  //   if (!row) {
  //     return `${this.isAllSelected() ? 'select' : 'deselect'} all`;
  //   }
  //   return `${this.selection.isSelected(row) ? 'deselect' : 'select'} row ${row.position + 1}`;
  // }
}
