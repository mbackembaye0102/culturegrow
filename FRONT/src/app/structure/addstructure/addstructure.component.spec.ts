import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AddstructureComponent } from './addstructure.component';

describe('AddstructureComponent', () => {
  let component: AddstructureComponent;
  let fixture: ComponentFixture<AddstructureComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AddstructureComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AddstructureComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
