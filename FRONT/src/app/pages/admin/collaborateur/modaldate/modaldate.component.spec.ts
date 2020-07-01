import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ModaldateComponent } from './modaldate.component';

describe('ModaldateComponent', () => {
  let component: ModaldateComponent;
  let fixture: ComponentFixture<ModaldateComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ModaldateComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ModaldateComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
