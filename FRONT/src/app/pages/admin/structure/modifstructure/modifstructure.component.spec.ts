import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ModifstructureComponent } from './modifstructure.component';

describe('ModifstructureComponent', () => {
  let component: ModifstructureComponent;
  let fixture: ComponentFixture<ModifstructureComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ModifstructureComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ModifstructureComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
