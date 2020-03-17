import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AddteamstructureComponent } from './addteamstructure.component';

describe('AddteamstructureComponent', () => {
  let component: AddteamstructureComponent;
  let fixture: ComponentFixture<AddteamstructureComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AddteamstructureComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AddteamstructureComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
