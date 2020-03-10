import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AddTeamFunctionComponent } from './add-team-function.component';

describe('AddTeamFunctionComponent', () => {
  let component: AddTeamFunctionComponent;
  let fixture: ComponentFixture<AddTeamFunctionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AddTeamFunctionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AddTeamFunctionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
