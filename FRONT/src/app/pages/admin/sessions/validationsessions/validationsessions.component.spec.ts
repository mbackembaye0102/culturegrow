import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ValidationsessionsComponent } from './validationsessions.component';

describe('ValidationsessionsComponent', () => {
  let component: ValidationsessionsComponent;
  let fixture: ComponentFixture<ValidationsessionsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ValidationsessionsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ValidationsessionsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
